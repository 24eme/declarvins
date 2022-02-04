<?php

class WidgetFacture extends sfWidgetFormChoice
{
    protected $identifiant = null;

    public function __construct($options = array(), $attributes = array())
    {
        parent::__construct($options, $attributes);
        $this->setOption('choices', array());
        $this->setAttribute('data-ajax', $this->getUrlAutocomplete());
    }

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->setAttribute('class', 'autocomplete');
        $this->setOption('choices', array());
    }

    public function getUrlAutocomplete() {
        return sfContext::getInstance()->getRouting()->generate('facture_autocomplete');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $this->identifiant = $value;
        return parent::render($name, $value, $attributes, $errors);
    }

}
