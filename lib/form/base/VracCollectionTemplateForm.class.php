<?php

class VracCollectionTemplateForm extends BaseForm {

    protected $form = null;
    protected $field = null;
    protected $form_embed = null;

    public function __construct($form, $field, $form_embed, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->form = $form;
        $this->field = $field;
        $this->form_embed = $form_embed;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        
        $this->embedForm('var---nbItem---', $this->form_embed);
        $name = sprintf($this->form->widgetSchema->getNameFormat(), $this->field);
        $this->widgetSchema->setNameFormat($name.'[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    }
    
    public function getFormTemplate() {
        
        return $this['var---nbItem---'];
    }
    
}