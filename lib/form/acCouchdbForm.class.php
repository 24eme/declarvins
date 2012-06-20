<?php

class acCouchdbForm extends BaseForm implements acCouchdbFormDocableInterface
{

  protected $docable = null;

  public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {
    $this->docable = new acCouchdbFormDocable($this, $doc);
    parent::__construct($defaults, $options, $CSRFSecret);
    $this->docable->init();
  }

  public function getDocable() {
        
    return $this->docable;
  }

  public function embedForm($name, sfForm $form, $decorator = null) {
    $this->docable->beforeEmbedForm($name, $form, $decorator);
    parent::embedForm($name, $form, $decorator);
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    parent::bind($taintedValues, $taintedFiles);
    $this->docable->postBind($taintedValues, $taintedFiles);
  }

}