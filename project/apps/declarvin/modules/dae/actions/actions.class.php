<?php
class daeActions extends acVinDAEActions {
	
    public function fileErrorUploadEdi($file, $etablissement, $periode) {
    	if ($file) {
    		Email::getInstance()->daeErrorFileSend($file, $etablissement);
    		$this->getUser()->setFlash("notice", 'Votre fichier a bien été importé. Il sera traité dans les plus bref délais.');
    		return $this->redirect('dae_upload_fichier_edi', array('identifiant' => $etablissement->identifiant, 'periode' => $periode->format('Y-m-d'), 'md5' => "0"));
    	}
    	return;
    }
    
}
