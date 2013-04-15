<?php
class DAIDSCampagneForm extends sfForm
{
    protected $etablissement = null;

    public function __construct($etablissement, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        $this->etablissement = $etablissement;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

	public function configure() 
	{    
        $this->setWidgets(array(
            'campagne'   => new sfWidgetFormChoice(array(
        						'choices' => $this->getCampagneChoices(),
        		)) //WidgetFormCampagne()
        ));

        $this->setValidators(array(
            'campagne'   => new ValidatorCampagne()
        ));
        
        $this->widgetSchema->setNameFormat('create_daids[%s]');
        $this->validatorSchema->setPostValidator(new DAIDSCampagneValidator(array('etablissement' => $this->etablissement)));
    }
    
    public function getCampagneChoices()
    {
    	$years = range(date('Y') + 1, date('Y') - 10);
    	$choices = array();
    	foreach ($years as $year) {
    		$choices[$year] = 'Août '.$year;
    	}
    	return $choices;
    }
}