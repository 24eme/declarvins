<?php
class daeActions extends acVinDAEActions {
	
    public function fileErrorUploadEdi($file, $files, $etablissement, $periode) {
    	if (is_array($files) && count($files) > 0) {
	    	if ($resultat = move_uploaded_file($files['tmp_name'], '/tmp/'.strtolower($files['name']))) {
	    		$file = '/tmp/'.strtolower($files['name']);
	    	}
    	}
    	if ($file) {
    		Email::getInstance()->daeErrorFileSend($file, $etablissement);
    		$this->getUser()->setFlash("notice", 'Votre fichier a bien été importé. Il sera traité dans les plus bref délais.');
    		return $this->redirect('dae_upload_fichier_edi', array('identifiant' => $etablissement->identifiant, 'periode' => $periode->format('Y-m-d'), 'md5' => "0"));
    	}
    	return;
    }
    
}
