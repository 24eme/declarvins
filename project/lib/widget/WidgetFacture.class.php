<?php

class WidgetFacture extends sfWidgetFormChoice
{
    protected $identifiant = null;
    public $interpro = null;

    public function __construct($interpro, $options = array(), $attributes = array())
    {
        $this->interpro = $interpro;
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
        return sfContext::getInstance()->getRouting()->generate('facture_autocomplete').'?interpro='.$this->interpro->_id;
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $this->identifiant = $value;
        return parent::render($name, $value, $attributes, $errors);
    }

}
