<?php
class ContratEtablissementNouveauForm extends acCouchdbFormDocumentJson {
	
    public function configure() {
        $this->setWidgets(array(
                'raison_sociale' => new sfWidgetFormInputText(),
	        'siret_cni' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
                'raison_sociale' => 'Raison sociale*: ',
		'siret_cni' => 'SIRET/CNI*: '
        ));

        $this->setValidators(array(
                'raison_sociale' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
		'siret_cni' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
        ));
		$this->widgetSchema->setNameFormat('contratetablissement[%s]');
        $this->mergePostValidator(new ValidatorEtablissementSiretCni());
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (isset($values['type_numero'])) {
        	if ($values['type_numero'] == 'siret') {
        		$this->getObject()->setSiret($values['siret_cni']);
        	} elseif($values['type_numero'] == 'cni') {
        		$this->getObject()->setCni($values['siret_cni']);
        	}
        }
    }
	
}