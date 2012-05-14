<?php
class CompteTiersAjoutForm extends CompteForm {
	
	public function configure() {
		if (!$this->getOption('contrat'))
			throw new sfException('l\'objet contrat doit être passé au CompteTiersAjoutForm');
		parent::configure();
	}
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->setMotDePasseSSHA($values['mdp1']);
    }

}