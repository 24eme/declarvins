<?php
class VracValideForm extends acCouchdbObjectForm
{
    
    protected static $_francize_date = array(
    	'date_saisie',
    );
	public function configure()
	{
		$this->setWidgets(array(
	       'date_saisie' => new sfWidgetFormInputText(),
	       'identifiant' => new sfWidgetFormInputText(),
	       'statut' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
	       'date_saisie' => 'Date de saisie:',
	       'identifiant' => 'Identifiant:',
	       'statut' => 'Statut:'
		));
		$this->setValidators(array(
	       'date_saisie' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false), array('invalid' => 'Format valide : dd/mm/aaaa')),
	       'identifiant' => new sfValidatorString(array('required' => false)),
	       'statut' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('[%s]');
	}
	

    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        foreach (self::$_francize_date as $field) {
        	if (isset($defaults[$field]) && !empty($defaults[$field])) {
        		$date = new DateTime($defaults[$field]);
        		$defaults[$field] = $date->format('d/m/Y');
        	}
        }    
        $this->setDefaults($defaults);     
    }
}