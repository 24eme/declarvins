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
    		
    		$result[$etablissement->key[1]] = $this->makeLibelle($etablissement->key); 
    	}
    	return $result;
    }
    
    public function setName($name)
    {
    	$name = ($name)? $name : 'etablissement_selection';
    	$this->widgetSchema->setNameFormat($name.'[%s]');
    }
    
    private function makeLibelle($datas) {
    	$etablissementLibelle = '';
    	if ($nom = $datas[2]) {
    		$etablissementLibelle .= $nom;
    	}
    	if ($rs = $datas[4]) {
    		if ($etablissementLibelle) {
    			$etablissementLibelle .= ' / ';
    		}
    		$etablissementLibelle .= $rs;
    	}
    	$etablissementLibelle .= ' ('.$datas[3];
    	if ($siret = $datas[5]) {
    		$etablissementLibelle .= ' / '.$siret;
    	}
    	if ($cvi = $datas[6]) {
    		$etablissementLibelle .= ' / '.$cvi;
    	}
    	$etablissementLibelle .= ') '.$datas[7].' '.$datas[8];
    	return trim($etablissementLibelle);
    }
    
}