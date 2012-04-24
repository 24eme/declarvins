<?php
class EtablissementSelectionForm extends sfForm {
	
	protected $interpro;
	
	public function __construct($interpro, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
  		$this->interpro = $interpro;
  		parent::__construct($defaults = array(), $options = array(), $CSRFSecret = null);
  	}
	public function configure() {
    	$this->setWidgets(array(
			'etablissement' => new sfWidgetFormChoice(array('choices' => $this->getEtablissements())) 		
    	));
		$this->widgetSchema->setLabels(array(
			'etablissement' => 'Etablissement*: ',
		));
		$this->setValidators(array(
			'etablissement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getEtablissements()))),
		));
        $this->widgetSchema->setNameFormat('etablissement_selection[%s]');
    }
    
    private function getEtablissements() {
    	$etablissements = EtablissementClient::getInstance()->findByInterpro($this->interpro);
    	$result = array('' => '');
    	foreach ($etablissements->rows as $etablissement) {
    		$result[$etablissement->key[1]] = $etablissement->key[2].' - '.$etablissement->key[3]; 
    	}
    	return $result;
    }
    
}