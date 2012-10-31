<?php
class ContratEtablissementNouveauForm extends acCouchdbObjectForm {
	
    public function configure() {
        
        $siretCni_errors = array('invalid' => 'Le Siret/Cni doit être soit un numéro de siret (14 chiffres) soit un numéro Cni (12 chiffres ou lettres majuscules)');
        $cniValid = new ValidatorCni();
        $siretValid = new ValidatorSiret();
        $this->setWidgets(array(
                'raison_sociale' => new sfWidgetFormInputText(),
        		 'nom' => new sfWidgetFormInputText(),
	        'siret_cni' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
                'raison_sociale' => 'Raison sociale*: ',
                'nom' => 'Nom commercial*: ',
		'siret_cni' => 'SIRET/CNI*: '
        ));
        $this->setValidators(array(
                'raison_sociale' => new sfValidatorString(array('required' => true, 'min_length' => 3),
                                                        array('required' => 'Champ obligatoire', 'min_length' => 'La raison sociale doit faire au minimum 3 caractères')),
                'nom' => new sfValidatorString(array('required' => true, 'min_length' => 2),array('required' => 'Champ obligatoire', 'min_length' => 'Le nom doit faire au minimum 2 caractères')),
		
                'siret_cni' => new sfValidatorOr(array($siretValid,$cniValid),array(),$siretCni_errors)));
		$this->widgetSchema->setNameFormat('contratetablissement[%s]');
        $this->mergePostValidator(new ValidatorEtablissementSiretCni());
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }
	
}