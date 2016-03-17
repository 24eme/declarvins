<?php

class DRMRnaCollectionTemplateForm extends BaseForm {

    protected $form = null;
    protected $form_embed = null;
    protected $unique_var = null;

    public function __construct($form, $form_embed, $unique_var = 'var---nbItem---',$defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->form = $form;
        $this->form_embed = $form_embed;
        $this->unique_var = $unique_var;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        
        $this->embedForm($this->unique_var, $this->form_embed);
        $name = sprintf($this->form->widgetSchema->getNameFormat(), 'rna');
        $this->widgetSchema->setNameFormat($name.'[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    }
    
    public function getFormTemplate() {
        
        return $this[$this->unique_var];
    }
    
}