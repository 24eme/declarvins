<?php

class VracCollectionTemplateForm extends BaseForm {

    protected $form = null;
    protected $field = null;
    protected $form_item = null;

    public function __construct($form, $field, $form_item_class, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->form = $form;
        $this->field = $field;
        $vrac = new Vrac();
        $this->form_item = new $form_item_class($vrac->get($this->field)->add());
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        
        $this->embedForm('var---nbItem---', $this->form_item);
        $name = sprintf($this->form->widgetSchema->getNameFormat(), $this->field);
        $this->widgetSchema->setNameFormat($name.'[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    }
    
    public function getFormTemplate() {
        
        return $this['var---nbItem---'];
    }
    
}