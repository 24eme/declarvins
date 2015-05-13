<?php

class VracModificationForm extends VracForm
{
    protected static $_francize_date = array(
        'date_signature',
    );
    public function configure()
    {
        $this->setWidgets(array(
        	'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'cas_particulier' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getCasParticulier())),
        	'volume_propose' => new sfWidgetFormInputFloat(),
        	'date_signature' => new sfWidgetFormInputText()
    	));
    	$this->widgetSchema->setLabels(array(
        	'premiere_mise_en_marche' => 'Première mise en marché:',
        	'cas_particulier' => 'Condition particulière:',
        	'volume_propose' => 'Volume total proposé*:',
        	'date_signature' => 'Date de signature*:'
        ));
        $this->setValidators(array(
        	'premiere_mise_en_marche' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'cas_particulier' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCasParticulier()))),
        	'volume_propose' => new sfValidatorNumber(array('required' => true, 'min' => $this->getObject()->volume_enleve), array('min' => "Le volume proposé doit être supérieur au volume enlevé")),
        	'date_signature' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true))
        ));

        $this->validatorSchema->setPostValidator(new VracMarcheValidator());

        $this->widgetSchema->setNameFormat('vrac_modification[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (isset($values['date_signature']) && $values['date_signature']) {
            $date = new DateTime($values['date_signature']);
            $this->getObject()->getDocument()->date_signature = $date->format('c');
        }
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
        $defaults['email'] = 1;
        $this->setDefaults($defaults);     
    }
}