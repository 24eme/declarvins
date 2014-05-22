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
    
    $csv_file = 'Identifiant;Raison Sociale;Nom Com.;Adresse;Code postal;Commune;Pays;Email;Tel.;Fax;Douane;';
    foreach ($bilan->getPeriodes() as $periode){
    	$csv_file .= "$periode;";
    }
	$csv_file .= "\n";		
    $etablissementsInformations = $bilan->getEtablissementsInformations();
    $drmsInformations = $bilan->getDRMsInformations();
    foreach ($bilan->getEtablissementsInformations() as $identifiant => $etablissement) {
		$informations = $etablissementsInformations[$identifiant];
		$csv_file .= $identifiant.';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_EMAIL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_TELEPHONE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_FAX].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_SERVICE_DOUANE].';';
		$drms = $drmsInformations[$identifiant];
		$precedente = null;
		foreach ($bilan->getPeriodes() as $periode) {
				if (!isset($drms[$periode]) && !$precedente) {
    				$first = DRMAllView::getInstance()->getFirstDrmPeriodeByEtablissement($identifiant); 
    				if($first && $periode < $first)
    					$csv_file .= ';';
    				else 
    					$csv_file .= '0;';
    			}
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
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=bilan_drm".$campagne.".csv");
    return $this->renderText($csv_file);
  }
  
  public function executeBilanDrmManquantesCsv(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '1024M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $periode = $request->getParameter('periode');
    $interpro = $request->getParameter('interpro');
    if (!$campagne) {
		return $this->renderText("Pas de campagne définie");
    }
    if (!$periode) {
		return $this->renderText("Pas de période définie");
    }
    if (!preg_match("/[0-9]{4}-[0-9]{4}/", $campagne)) {
    	return $this->renderText("Campagne invalide");
    }
    if (!preg_match("/[0-9]{4}-[0-9]{2}/", $periode)) {
    	return $this->renderText("Période invalide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $bilan = new StatistiquesBilan($interpro, $campagne, $periode);
    $manquantes = $bilan->getVolumesAnterieursDRMManquantes();
    
    $csv_file = 'Identifiant;Raison Sociale;Nom Com.;Adresse;Code postal;Commune;Pays;Email;Tel.;Fax;Douane;Categorie;Genre;Denomination;Lieu;Couleur;Cepage;'.$periode.';';
    /*foreach ($bilan->getPeriodes() as $periode){
    	$csv_file .= "$periode;";
    }*/
	$csv_file .= "\n";
    foreach ($manquantes as $identifiant => $periodes) {
		$informations = $bilan->getEtablissementInformations($identifiant);
		foreach ($periodes as $per => $drm) {
			$produits = $drm->getDetails();
			foreach ($produits as $produit) {
				$csv_file .= $identifiant.';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_EMAIL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_TELEPHONE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_FAX].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_SERVICE_DOUANE].';';
				$csv_file .= $produit->getCertification()->getKey().';'.$produit->getGenre()->getKey().';'.$produit->getAppellation()->getKey().';'.$produit->getLieu()->getKey().';'.$produit->getCouleur()->getKey().';'.$produit->getCepage()->getKey().';';
				$csv_file .= $produit->cvo->volume_taxable.';';
				/*foreach ($bilan->getPeriodes() as $p) {
					if ($p == $per) {
						$csv_file .= $produit->total.';';
					} else {
						$csv_file .= ';';
					}
				}*/
				$csv_file .= "\n";
			}
		}
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $filename = date('Y-m-d')."_drm_manquantes_n-1_".$campagne."_".$periode.".csv";
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=$filename");
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
  
  public function executeDrmStatistiques(sfWebRequest $request) 
  {
  	  $this->page = $request->getParameter('p', 1);
  	  $this->statistiquesConfig = sfConfig::get('app_statistiques_drm');
  	  if (!$this->statistiquesConfig) {
  	  	throw new sfException('No configuration set for elasticsearch type drm');
  	  }
	  $this->form = new StatistiqueDRMFilterForm($this->getUser()->getCompte()->getGerantInterpro());
	  $this->query = $this->form->getDefaultQuery();
	  if ($request->isMethod(sfWebRequest::POST)) {
	  	$this->form->bind($request->getParameter($this->form->getName()));
  	  	if ($this->form->isValid()) {
  	  		if ($q = $this->form->getQuery()) {
  	  			$this->query = $q;
  	  		}
  	  	}
	  }  
	  $this->produits = $this->form->getProduits();
	  $this->filtres = $this->form->getFiltres();
      
      $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
      $elasticaQuery = new acElasticaQuery();
      $elasticaQuery->setQuery($this->query);
      
      
  	  //print_r(json_encode($elasticaQuery->toArray()));exit;
      
      $facets = $this->statistiquesConfig['facets'];
      foreach($facets as $facet) {
		$elasticaFacet 	= new acElasticaFacetStatistical($facet['nom']);
		if ($this->produits) {
			$script = null;
			foreach ($this->produits as $produit) {
					if ($script) {
						$script .= ' + ';
					}
					$script .= "doc['".$produit.".".$facet['noeud']."'].value";
    		}
    		if (count($this->produits) > 1) {
				$elasticaFacet->setScript($script);
    		} else {
    			$elasticaFacet->setField($produit.'.'.$facet['noeud']);
    		}
		} else {
			$elasticaFacet->setField('declaration.'.$facet['noeud']);
		}
		$elasticaQuery->addFacet($elasticaFacet);
      }
      
  	  $facetsGraph = $this->statistiquesConfig['facets_graph'];
      foreach($facetsGraph as $facetGraph) {
		$elasticaFacet 	= new acElasticaFacetDateHistogram($facetGraph['nom']);
		$elasticaFacet->setField($facetGraph['key_field']);
		$elasticaFacet->setInterval($facetGraph['interval']);
		if ($this->produits) {
			$script = null;
			foreach ($this->produits as $produit) {
					if ($script) {
						$script .= ' + ';
					}
					$script .= "doc['".$produit.".".$facetGraph['value_field']."'].value";
    		}
    		if (count($this->produits) > 1) {
				$elasticaFacet->setKeyValueScripts($facetGraph['key_field'], $script);
    		} else {
    			$elasticaFacet->setKeyValueFields($facetGraph['key_field'], $produit.'.'.$facetGraph['value_field']);
    		}
		} else {
			$elasticaFacet->setKeyValueFields($facetGraph['key_field'], 'declaration.'.$facetGraph['value_field']);
		}
		$elasticaQuery->addFacet($elasticaFacet);
      }
      
      
  	  //print_r(json_encode($elasticaQuery->toArray()));exit;
      $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
      $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
      $result = $index->search($elasticaQuery);
      $this->hits = $result->getResults();
      $this->facets = $this->getStatisticalFacets($result->getFacets());
      $this->chartConfig = $this->getChartConfig($this->getDateHistogramFacets($result->getFacets()));
      $this->nbHits = $result->getTotalHits();
      $this->nbPage = ceil($this->nbHits / $this->statistiquesConfig['nb_resultat']);      

  }
  
  private function getStatisticalFacets($facets)
  {
  	$statistical = array();
  	foreach ($facets as $facetKey => $facet) {
  		if ($facet['_type'] == 'statistical') {
  			$statistical[$facetKey] = $facet;
  		}
  	}
  	return $statistical;
  }
  
  private function getDateHistogramFacets($facets)
  {
  	$dateHistogram = array();
  	foreach ($facets as $facetKey => $facet) {
  		if ($facet['_type'] == 'date_histogram') {
  			$dateHistogram[$facetKey] = $facet;
  		}
  	}
  	return $dateHistogram;
  }
  
  private function getChartConfig($datas)
  {
  	$config = array();
  	$first = true;
  	foreach ($datas as $name => $values) {
  		foreach ($values['entries'] as $entries) {
  			if ($first) {
  				$config['categories'][] = date('Y-m', $entries['time']/1000);
  			}
  			$config['series'][$name][] = $entries['count'];
  		}
  		$first = false;
  	}
  	return $config;
  }
  
  public function executeVracStatistiques(sfWebRequest $request) 
  {
  	  $this->page = $request->getParameter('p', 1);
  	  $this->statistiquesConfig = sfConfig::get('app_statistiques_vrac');
  	  if (!$this->statistiquesConfig) {
  	  	throw new sfException('No configuration set for elasticsearch type vrac');
  	  }
	  $this->form = new StatistiqueVracFilterForm($this->getUser()->getCompte()->getGerantInterpro());
	  $this->query = $this->form->getDefaultQuery();
	  if ($request->isMethod(sfWebRequest::POST)) {
	  	$this->form->bind($request->getParameter($this->form->getName()));
  	  	if ($this->form->isValid()) {
  	  		if ($q = $this->form->getQuery()) {
  	  			$this->query = $q;
  	  		}
  	  	}
	  }  
	  $this->produits = $this->form->getProduits();
      
      $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
      $elasticaQuery = new acElasticaQuery();
      $elasticaQuery->setQuery($this->query);
      
      
  	  //print_r(json_encode($elasticaQuery->toArray()));exit;
      
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
      
      $facetsGraph = $this->statistiquesConfig['facets_graph'];
      foreach($facetsGraph as $facetGraph) {
		$elasticaFacet 	= new acElasticaFacetDateHistogram($facetGraph['nom']);
		$elasticaFacet->setField($facetGraph['key_field']);
		$elasticaFacet->setInterval($facetGraph['interval']);
		$elasticaQuery->addFacet($elasticaFacet);
      }
      
      $elasticaQuery->setLimit($this->statistiquesConfig['nb_resultat']);
      $elasticaQuery->setFrom(($this->page - 1) * $this->statistiquesConfig['nb_resultat']);
      $result = $index->search($elasticaQuery);
      $this->hits = $result->getResults();
      $this->facets = $result->getFacets();
      $this->chartConfig = $this->getChartConfig($this->getDateHistogramFacets($result->getFacets()));
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
