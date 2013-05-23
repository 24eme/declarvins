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
      }
  	  $this->bilan = new StatistiquesBilan($this->interpro->get('_id'), $this->campagne);
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
  	  
  	  /*
  	   * BLOC FILTRES -------
  	   * Pour le moment commun a tous!
  	   * A rendre generique quand on aura les difference
  	   */
	  $this->form = new StatistiqueFilterForm();
	  $query = '*';
	  $this->query = '';
  	  if ($datas = $request->getParameter($this->form->getName())) {
      	$this->form->bind($datas);
  	  	if ($this->form->isValid()) {
  	  		$values = $this->form->getValues();
  	  		if ($values['query']) {
  	  			$this->query = $values['query'];
  	  			$query = $values['query'];
  	  		}
  	  	}
      }   
      /*
       * -------- BLOC FILTRES
       */
      
      $index = acElasticaManager::getType($this->statistiquesConfig['elasticsearch_type']);
      $elasticaQuery = new acElasticaQuery();
      $elasticaQuery->setQuery(new acElasticaQueryQueryString($query));
      
      $facets = $this->statistiquesConfig['facets'];
      foreach($facets as $facet) {
		$elasticaFacet 	= new acElasticaFacetStatistical($facet['nom']);
		$elasticaFacet->setField($facet['noeud']);
		if ($facet['code']) {
			$elasticaFacet->setScript($facet['code']);
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
  
}
