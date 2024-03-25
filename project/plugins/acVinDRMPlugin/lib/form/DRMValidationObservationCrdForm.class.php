<?php

class DRMValidationObservationCrdForm extends BaseForm
{
	protected $_crd;

	public function __construct($crd, $options = array(), $CSRFSecret = null)
	{
		$this->_crd = $crd;
		parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
	}

    public function getDefaultValues() {
    	$defaults = array(
    			'observations' => $this->_crd->observations
    	);
    	return  $defaults;
    }

	public function configure()
	{
		$this->setWidgets(array(
            'observations' => new sfWidgetFormInput()
        ));
        $this->setValidators(array(
            'observations' => new sfValidatorString(array('required' => false))
        ));
        $this->widgetSchema->setLabels(array(
        	'observations' => 'CRD '.$this->_crd->libelle
        ));
        $this->widgetSchema->setNameFormat('[%s]');
	}
}
