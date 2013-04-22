<?php
class LibelleAdminForm extends sfForm {
	
  protected $object;
  protected $key;
	
  public function __construct($object, $key, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->object = $object;
    $this->key = $key;
    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function configure() 
  {
	$this->setWidgets(array(
		'libelle' => new sfWidgetFormTextarea()
	));
	$this->setValidators(array(
		'libelle' => new sfValidatorString(array('required' => false), array('required' => 'Champ obligatoire'))
	));
	$this->widgetSchema->setLabels(array(
		'libelle' => 'Libellé: '
	));
	$this->setDefault('libelle', $this->object->get($this->key));
	$this->widgetSchema->setNameFormat('libelle[%s]');
  }
  
  public function save()
  {
  	$values = $this->getValues();
  	$this->object->set($this->key, $values['libelle']);
  	$this->object->getDocument()->save();
  }
}