<?php
class VracPaiementForm extends acCouchdbObjectForm
{
    
    protected static $_francize_date = array(
    	'date',
    );
	public function configure()
	{
		$this->setWidgets(array(
	       'date' => new sfWidgetFormInputText(),
		   'montant' => new sfWidgetFormInputFloat()
		));
		$this->widgetSchema->setLabels(array(
	       'date' => 'Date du paiement :',
	       'montant' => 'Montant :'
		));
		$this->setValidators(array(
	       'date' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false), array('invalid' => 'Format valide : dd/mm/aaaa')),
	       'montant' => new sfValidatorNumber(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('paiement[%s]');
	}
	

    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        foreach (self::$_francize_date as $field) {
        	if (isset($defaults[$field]) && !empty($defaults[$field])) {
        		$defaults[$field] = Date::francizeDate($defaults[$field]);
        	}
        }   
        $this->setDefaults($defaults);      
    }
}