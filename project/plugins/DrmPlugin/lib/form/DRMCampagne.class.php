<?php
class DRMCampagne extends sfForm
{
	public function configure() {    
        $this->setWidgets(array(
            'months'   => new sfWidgetFormChoice(array('choices' => $this->getMonths())),
        	'years'   => new sfWidgetFormChoice(array('choices' => $this->getYears()))
        ));

        $this->setValidators(array(
            'months'   => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getMonths()))),
        	'years'   => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getYears())))
        ));
        
        $this->widgetSchema->setNameFormat('campagne[%s]');
        $this->validatorSchema->setPostValidator(new DRMCampagneValidator());
    }
    
    public function getMonths() {
	    $results = array('' => '');
	    for ($i = 1; $i <= 12; $i++)
	    {
	      $results[$i] = sprintf('%02d', $i);
	    }
	    return $results;
    }
    public function getYears() {
    	$years = range(date('Y'), date('Y') - 10);
    	array_unshift($years, '');
    	$years = array_combine($years, $years);
    	return $years;
    }
}