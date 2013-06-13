<?php

/**
 * statistique actions.
 *
 * @package    declarvin
 * @subpackage statistique
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statistiqueActions extends sfActions
{

  /**
   *
   * @param sfWebRequest $request 
   */  
  public function executeBilanDrm(sfWebRequest $request) 
  {
  	  $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
  	  $this->campagne =  null;
  	  $this->bilan = null;
  	  $this->formCampagne = new CampagneForm();
  	  if ($datas = $request->getParameter($this->formCampagne->getName())) {
    	$this->formCampagne->bind($datas);
  	  	if ($this->formCampagne->isValid()) {
  	  		$values = $this->formCampagne->getValues();
  	  		$this->campagne = $values['campagne'];
  	  	}
      }
      if (!$this->campagne) {
  	  	$this->campagne =  DRMClient::getInstance()->buildCampagne(date('Y-m'));
  	  	$campagne = explode('-', $this->campagne);
  	  	$this->formCampagne->setDefault('campagne', $campagne[0]);
      } else {
  	  	$this->bilan = new StatistiquesBilan($this->interpro->get('_id'), $this->campagne);
      }
  }
  
  public function executeBilanDrmCsv(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '1024M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
    if (!$campagne) {
		return $this->renderText("Pas de campagne définie");
    }
    if (!preg_match("/[0-9]{4}-[0-9]{4}/", $campagne)) {
    	return $this->renderText("Campagne invalide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $bilan = new StatistiquesBilan($interpro, $campagne);
    
    $csv_file = 'Etablissements;';
    foreach ($bilan->getPeriodes() as $periode){
    	$csv_file .= "$periode;";
    }
	$csv_file .= "\n";		
    $etablissementsInformations = $bilan->getEtablissementsInformations();
    $drmsInformations = $bilan->getDRMsInformations();
    foreach ($bilan->getEtablissementsInformations() as $identifiant => $etablissement) {
		$informations = $etablissementsInformations[$identifiant];
		$csv_file .= $informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE].' '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM].' ('.$identifiant.') '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE].' '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL].' '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE].' '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS].' '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_EMAIL].' Tel : '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_TELEPHONE].' Fax : '.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_FAX].';';
		$drms = $drmsInformations[$identifiant];
		$precedente = null;
		foreach ($bilan->getPeriodes() as $periode) {
    			if (!isset($drms[$periode]) && !$precedente)
    			$csv_file .= '0;';
    			elseif (!isset($drms[$periode]) && $precedente && $precedente[StatistiquesBilanView::VALUE_DRM_TOTAL_FIN_DE_MOIS] > 0)
    			$csv_file .= '0;';
    			elseif (isset($drms[$periode]) && !$drms[$periode][StatistiquesBilanView::VALUE_DRM_DATE_SAISIE])
    			$csv_file .= '0;';
    			else
    			$csv_file .= '1;';
    			if (isset($drms[$periode])) {
    				$precedente = $drms[$periode];
    			}
		}
	$csv_file .= "\n";
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$campagne.".csv");
    return $this->renderText($csv_file);
  }
  
  private function parseQueryForDefaultValuesForm($query)
  {
  	$result = array();
  	if ($query) {
  		$fields = explode(' ', $query);
  		foreach ($fields as $field) {
  			$terms = explode(':', $field);
  			$result[$terms[0]] = $terms[1];
  		}
  	}
  	return $result;
  }
  
  public function executeStatistiques(sfWebRequest $request) 
  {
  	  $this->page = $request->getParameter('p', 1);
  	  $this->type = $request->getParameter('type');
  	  if (!$this->type) {
  	  	throw new sfException('You must specify elasticsearch type');
  	  }
  	  $this->statistiquesConfig = sfConfig::get('app_statistiques_'.$this->type);
  	  if (!$this->statistiquesConfig) {
  	  	throw new sfException('No configuration set for elasticsearch type '.$this->type);
  	  }
  	  
  	  $this->query = $request->getParameter('query');
	  $this->form = FilterFormFactory::create($this->type, $this->getUser()->getCompte()->getGerantInterpro());
	  if ($this->query) {
	  	$this->form->setDefaults($this->parseQueryForDefaultValuesForm($this->query));
	  } else {
	  	$this->query = $this->form->getDefaultQuery();
	  }
	  if ($request->isMethod(sfWebRequest::POST)) {
	  	$this->form->bind($request->getParameter($this->form->getName()));
  	  	if ($this->form->isValid()) {
  	  		if ($q = $this->form->getQuery()) {
  	  			$this->query = $q;
  	  		}
  	  	}
	  }  
	  $this->hashProduitFilter = $this->form->getProduit();
      
      $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
      $elasticaQuery = new acElasticaQuery();
      $elasticaQuery->setQuery(new acElasticaQueryQueryString($this->query));
      
      $facets = $this->statistiquesConfig['facets'];
      foreach($facets as $facet) {
		$elasticaFacet 	= new acElasticaFacetStatistical($facet['nom']);
		if ($facet['noeud']) {
			if ($facet['need_replace']) {
				$elasticaFacet->setField(str_replace($facet['replace'], $this->{$facet['var_replace']}, $facet['noeud']));
			} else {
				$elasticaFacet->setField($facet['noeud']);
			}
		}
		if ($facet['code']) {
			$elasticaFacet->setScript($facet['code']);
		}
		if ($facet['facet_filter']) {
			$filters = $this->getFacetFilters($facet);
			foreach ($filters as $filter) {
				$elasticaFacet->setFilter($filter);
			}
		}
		$elasticaQuery->addFacet($elasticaFacet);
      }
      $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
      $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
      $result = $index->search($elasticaQuery);
      $this->hits = $result->getResults();
      $this->facets = $result->getFacets();
      $this->nbHits = $result->getTotalHits();
      $this->nbPage = ceil($this->nbHits / $this->statistiquesConfig['nb_resultat']);      

  }
  
  private function getFacetFilters($facet)
  {
  	  $filters = array();
  	  foreach ($facet['filters'] as $filter) {
  	  	switch ($filter['type']) {
  	  		case 'not':
  	  			 $filters[] = new acElasticaFilterNot(new acElasticaFilterTerm(array($filter['term']['node'] => $filter['term']['value'])));
  	  			 break;
  	  	    default: continue; break;
  	  	}
  	  }
  	  return $filters;
  }
}
