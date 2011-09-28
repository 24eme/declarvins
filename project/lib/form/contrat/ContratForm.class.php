<?php
class ContratForm extends sfCouchdbFormDocumentJson {

    public function configure() {
    	$this->setWidgets(array(
			'nom' => new sfWidgetFormInputText(),
			'prenom' => new sfWidgetFormInputText(),
			'fonction' => new sfWidgetFormInputText(),
			'telephone' => new sfWidgetFormInputText(),
			'fax' => new sfWidgetFormInputText()    		
    	));
		$this->widgetSchema->setLabels(array(
			'nom' => 'Nom*: ',
			'prenom' => 'Prénom*: ',
			'fonction' => 'Fonction*: ',
			'telephone' => 'Téléphone*: ',
			'fax' => 'Fax: '
		));
		$this->setValidators(array(
			'nom' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'prenom' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'fonction' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'telephone' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'fax' => new sfValidatorString(array('required' => false))
		));
		
		$formEtablissements = new ContratEtablissementCollectionForm(null, array(
	    	'contrat' => $this->getObject(),
	    	'nbEtablissement'    => $this->getOption('nbEtablissement', 1)
	  	));
  		$this->embedForm('etablissements', $formEtablissements);

        $this->widgetSchema->setNameFormat('contrat[%s]');
        

    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->isNew())
        {
        	$noContrat = sfCouchdbManager::getClient('Contrat')->getNextNoContrat();
            $this->getObject()->set('_id', 'CONTRAT-'.$noContrat);
            $this->getObject()->setNoContrat($noContrat);
        }
    }

}