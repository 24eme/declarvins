<?php

class DRMDeclaratifRnaForm extends BaseForm
{
	protected $_rna;
        
	public function __construct($rna, $options = array(), $CSRFSecret = null) 
	{
		$this->_rna = $rna;
		parent::__construct($this->getDefaultValues(), $options, $CSRFSecret);
	}

    public function getDefaultValues() {
    	$defaults = array(
    			'numero' => $this->_rna->numero,
    			'accises' => $this->_rna->accises,
    			'date' => Date::francizeDate($this->_rna->date)
    	);
    	return  $defaults;
    }
	
	
    	
	public function configure()
	{
		$this->setWidgets(array(
            'numero' => new sfWidgetFormInput(),
            'accises' => new sfWidgetFormInput(),
            'date' => new sfWidgetFormInput()
        ));
        $this->setValidators(array(
            'numero' => new sfValidatorInteger(array('required' => false)),
            'accises' => new sfValidatorRegex(array('required' => false, 'pattern' => '/^(FR0[a-z0-9]{10})$/', 'min_length' => 13)),
            'date' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false), array('invalid' => 'Format valide : dd/mm/aaaa'))
        ));
        $this->validatorSchema->setPostValidator(new DRMRnaValidator());
        $this->widgetSchema->setNameFormat('[%s]');
	}
}