<?php

class VracModificationForm extends VracForm
{
    protected static $_francize_date = array(
        'date_signature',
    );
    public function configure()
    {
        parent::configure();
        $this->useFields(array(
           'premiere_mise_en_marche',
           'cas_particulier',
           'volume_propose',
           'date_signature'
        ));

        $this->setValidator('volume_propose', new sfValidatorNumber(array('required' => true, 'min' => $this->getObject()->volume_enleve)));

        $this->getValidator('volume_propose')->setMessage('min', "Le volume proposé doit être supérieur au volume enlevé");

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