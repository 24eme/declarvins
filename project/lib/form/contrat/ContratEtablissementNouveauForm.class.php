<?php
class ContratEtablissementNouveauForm extends sfCouchdbFormDocumentJson {
	
    public function configure() {
        $this->setWidgets(array(
                'raison_sociale' => new sfWidgetFormInputText(),
        		'siret' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
                'raison_sociale' => 'Raison sociale*: ',
        		'siret' => 'SIRET*: '
        ));

        $this->setValidators(array(
                'raison_sociale' => new sfValidatorString(array('required' => true),array('required' => 'Champ obligatoire')),
        		'siret' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
        ));
        
		$this->widgetSchema->setNameFormat('contratetablissement[%s]');
    }
	
}