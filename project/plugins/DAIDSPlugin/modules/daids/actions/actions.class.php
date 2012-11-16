<?php

/**
 * daids actions.
 *
 * @package    declarvin
 * @subpackage daids
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class daidsActions extends sfActions
{

  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeNouvelle(sfWebRequest $request) 
  {
      $daids = $this->getRoute()->getDAIDS();
      if ($daids->getHistorique()->hasDAIDSInProcess()) {
        throw new sfException('Une DAI/DS est déjà en cours de saisie.');
      }
      if(DAIDSClient::getInstance()->formatToCompare($daids->periode) > DAIDSClient::getInstance()->formatToCompare(DAIDSClient::getInstance()->getCurrentPeriode())) {
        throw new sfException('Impossible de faire une DAI/DS future');
      }
      $daids->save();
      $this->redirect('daids_informations', $daids);
  }
  
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeInit(sfWebRequest $request) 
  {
      $daids = $this->getRoute()->getDAIDS();
      $reinit_etape = $request->getParameter('reinit_etape', 0);
      if ($reinit_etape) {
		$daids->setCurrentEtapeRouting('recapitulatif');
      	$this->redirect($daids->getCurrentEtapeRouting(), $daids);
      } elseif ($etape = $daids->etape) {
      	$this->redirect($daids->getCurrentEtapeRouting(), $daids);
      } else {
		$daids->setCurrentEtapeRouting('ajouts_liquidations');
      	$this->redirect('daids_informations', $daids);
      }
  }
  
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request)
  {
      $this->etablissement = $this->getRoute()->getEtablissement();
      $this->historique = DAIDSClient::getInstance()->getDAIDSHistorique($this->etablissement->identifiant);
      $this->formCampagne = new DAIDSCampagneForm($this->etablissement->identifiant);
      $this->hasDaidsEnCours = $this->historique->hasDAIDSInProcess();
      if ($request->isMethod(sfWebRequest::POST)) {
	  	if ($this->hasDaidsEnCours) {
	  		throw new sfException('Une DAIDS est déjà en cours de saisie.');
	  	}
    	$this->formCampagne->bind($request->getParameter($this->formCampagne->getName()));
  	  	if ($this->formCampagne->isValid()) {
  	  		$values = $this->formCampagne->getValues();
  	  		$daids = DAIDSClient::getInstance()->createDoc($this->etablissement->identifiant, $values['campagne']);
  	  		$daids->mode_de_saisie = DAIDSClient::MODE_DE_SAISIE_PAPIER;
      		$daids->save();
      		$this->redirect('daids_informations', $daids);
  	  	}
      }
  }
  
  public function executeDelete(sfWebRequest $request) {
      $etablissement = $this->getRoute()->getEtablissement();
      $daids = $this->getRoute()->getDAIDS();
      if (!$daids->isNew() && ($daids->isSupprimable() || ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $daids->isSupprimableOperateur()))) {
        $daidsList = DAIDSClient::getInstance()->findByIdentifiantAndPeriodeAndRectificative($daids->identifiant, $daids->periode, $daids->getRectificative());
        foreach($daidsList as $d) {
          $d->delete();
        }
      	$this->redirect('daids_mon_espace', $etablissement);
      }
      throw new sfException('Vous ne pouvez pas supprimer cette DAIDS');
  }

 /**
  * Executes informations action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformations(sfWebRequest $request)
  {
    $this->daids = $this->getRoute()->getDAIDS();
    $this->etablissement = $this->getRoute()->getEtablissement();
    $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR);
    $this->form = new DAIDSInformationsForm(array(), array('is_admin' => $isAdmin));
    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
  	  if ($this->form->isValid()) {
	  		$values = $this->form->getValues();
            if ($values['confirmation'] == "modification") {
            	$this->redirect('daids_modif_infos', $this->daids);
            } elseif ($values['confirmation']) {
            	$this->daids->setDeclarantInformations($this->etablissement);		
  				$this->daids->save();
	  		}
	        $this->daids->setCurrentEtapeRouting('recapitulatif');
	        return $this->redirect('daids_recap', $this->daids->declaration->certifications->getFirst());
    	}
    }
  }
  
  public function executeModificationInfos(sfWebRequest $request)
  {
      $this->daids = $this->getRoute()->getDAIDS();
      $this->etablissement = $this->getRoute()->getEtablissement();
  }
    
	public function executeValidee() 
	{
        $this->etablissement = $this->getRoute()->getEtablissement();
    }
    
	public function executeNonValidee() 
	{
		$this->etablissement = $this->getRoute()->getEtablissement();
	}
    
	public function executeHamza() 
	{
		
	}
  
}
