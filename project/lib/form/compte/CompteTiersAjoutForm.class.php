<?php
class CompteTiersAjoutForm extends CompteForm {
	
	public function configure() {
		if (!$this->getOption('contrat'))
			throw new sfException('l\'objet contrat doit être passé au CompteTiersAjoutForm');
		parent::configure();
	}
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->isNew())
        {
            $this->getObject()->set('_id', 'COMPTE-'.$this->getObject()->getLogin());
            $this->getObject()->setPasswordSSHA($values['mdp1']);
            // Duplication des infos du contrat dans le compte
            $contrat = $this->getOption('contrat');
            $this->getObject()->setContrat($contrat->get('_id'));
            $this->getObject()->setNom($contrat->getNom());
            $this->getObject()->setPrenom($contrat->getPrenom());
            $this->getObject()->setFonction($contrat->getFonction());
            $this->getObject()->setTelephone($contrat->getTelephone());
            $this->getObject()->setFax($contrat->getFax());
        }
    }

}