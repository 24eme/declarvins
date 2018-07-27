<?php
class dsnegoceActions extends sfActions {

    public function executeMonEspace(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->cm = new CampagneManager('08-01');
        $this->ds = DSNegoceClient::getInstance()->findByIdentifiant($this->etablissement->identifiant);
    }
    
    public function executeUpload(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->fichier = DSNegoceClient::getInstance()->createDoc($this->etablissement->identifiant, null, $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN));
    	$this->form = new DSForm($this->fichier);
    	
    	if (!$request->isMethod(sfWebRequest::POST)) {
    		return sfView::SUCCESS;
    	}
    	
    	$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    	
    	if (!$this->form->isValid()) {
    		return sfView::SUCCESS;
    	}
    	
    	$this->form->save();
    	return $this->redirect('dsnegoce_mon_espace', $this->etablissement);
    }
}