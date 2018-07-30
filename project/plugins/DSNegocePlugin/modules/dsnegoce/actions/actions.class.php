<?php
class dsnegoceActions extends sfActions {

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->cm = new CampagneManager('08-01');
        $this->isCloture = (date('Y-m-d') > sfConfig::get('app_dsnegoce_cloture'))? true : false;
        $this->history = PieceAllView::getInstance()->getPiecesByEtablissement($this->etablissement->identifiant, $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN));
    }
    
    public function executeUpload(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->isCloture = (date('Y-m-d') > sfConfig::get('app_dsnegoce_cloture'))? true : false;
    	if ($this->isCloture) {
    		return $this->redirect('dsnegoce_mon_espace', $this->etablissement);
    	}
    	$this->fichier = DSNegoceClient::getInstance()->createDoc($this->etablissement->identifiant, sfConfig::get('app_dsnegoce_date'), $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN));
    	$this->form = new DSForm($this->fichier);
    	
    	if (!$request->isMethod(sfWebRequest::POST)) {
    		return sfView::SUCCESS;
    	}
    	
    	$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    	
    	if (!$this->form->isValid()) {
    		return sfView::SUCCESS;
    	}
    	
    	$this->form->save();
    	
    	$interpro = InterproClient::getInstance()->find($this->form->getValue('interpro'));
    	
    	Email::getInstance()->dsnegoceSend($this->fichier, $this->etablissement, $interpro->email_dsnegoce);
    	
    	return $this->redirect('dsnegoce_mon_espace', $this->etablissement);
    }
}