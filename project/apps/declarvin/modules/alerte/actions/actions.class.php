<?php

/**
 * alerte actions.
 *
 * @package    declarvin
 * @subpackage alerte
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class alerteActions extends sfActions
{
  public function executeAlertes(sfWebRequest $request) {
  	  if ($request->getParameter('reset_filters', null)) {
  	  	$this->getUser()->setAttribute('alerte_filters', null);
  	  	$this->redirect('@alertes');
  	  }
      $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
	  $this->drms_manquantes = $this->getAlertesByOptions($this->interpro, DRMsManquantes::ALERTE_DOC_ID, $this->getUser()->getAttribute('alerte_filters'));
	  $this->form = new AlerteFiltersForm($this->interpro->_id, $this->getUser()->getAttribute('alerte_filters'));
	  if ($request->isMethod(sfWebRequest::POST)) {
	  	$this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$this->getUser()->setAttribute('alerte_filters', $this->form->getFormattedValuesForUrlParameters());
			$this->redirect('@alertes');
		}
	  }
  }
  public function executeAlerteModification(sfWebRequest $request)
  {
  	$this->forward404Unless($this->id = $request->getParameter('id'));
	if ($alerte = AlerteClient::getInstance()->find($this->id)) {
	  	$this->form = new AlerteAdminForm($alerte->getLastAlerte());
	  	if ($request->isMethod(sfWebRequest::POST)) {
	  		$this->form->bind($request->getParameter($this->form->getName()));
	  		if ($this->form->isValid()) {
	  			$this->form->save();
	  			$this->redirect('alertes');
	  		}
	  	}
	}
  }
  
  protected function getAlertesByOptions($interpro, $type, $options) 
  {
  	if (isset($options['campagne']) && !empty($options['campagnes']) && isset($options['etablissement']) && !empty($options['etablissement'])) {
  		return AlertesAllEtablissementsView::getInstance()->findByEtablissementAndCampagne($interpro->_id, $type, $options['etablissement'], $options['campagne']);
  	} elseif ((isset($options['campagne']) && !empty($options['campagne'])) && (!isset($options['etablissement']) || empty($options['etablissement']))) {
  		return AlertesAllView::getInstance()->findByCampagne($interpro->_id, $type, $options['campagne']);
  	} elseif ((!isset($options['campagne']) || empty($options['campagne'])) && (isset($options['etablissement']) && !empty($options['etablissement']))) {
  		return AlertesAllEtablissementsView::getInstance()->findByEtablissement($interpro->_id, $type, $options['etablissement']);
  	} else {
  		return AlertesAllView::getInstance()->findAll($interpro->_id, $type);
  	}
  }
  
}
