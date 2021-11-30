<?php

class DRMProduitEditForm extends acCouchdbForm 
{
    protected $_config = null;
    protected $detail = null;
    protected $label = null;

    public function __construct(acCouchdbJson $object, $config, $options = array(), $CSRFSecret = null) {
        $this->_config = $config;
        $this->detail = $object;
        $labels = $object->labels->toArray();
        $this->label = array_shift($labels);
        $defaults = array('libelle' => $object->libelle, 'labels' => $this->label); 
        parent::__construct($object->getDocument(), $defaults, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
    	$labels = $this->getLabels();
        $this->setWidgets(array(
            'libelle' => new sfWidgetFormInput(),
            'labels' => new sfWidgetFormChoice(array('expanded' => true,'choices' => $labels)),
        ));
        $this->widgetSchema->setLabels(array(
            'libelle' => 'Libelle CIEL* : ',
            'labels' => 'Label : ',
        ));

        $this->setValidators(array(
            'libelle' => new sfValidatorString(array('required' => true)),
            'labels' => new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($labels))),
        ));

        //$this->validatorSchema->setPostValidator(new DRMProduitValidator(null, array('drm' => $this->_drm)));
        $this->widgetSchema->setNameFormat('produit[%s]');
    }
    
    public function getLabels() 
    {
        $labels = $this->_config->getLabels($this->detail->getCertification()->getHash());

        return $labels;
    }

    public function hasLabel() {
        
        return count($this->getLabels()) > 0;
    }

    public function save() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }
        $labels = $this->getLabels();
        $this->detail->libelle = $this->values['libelle'];
        /*$labelValue = array_shift($this->values['labels']);
        if ($this->label != $labelValue) {
        	$this->detail->add('labels', array($labelValue));
        	$this->detail->add('libelles_label', array($labelValue => $labels[$labelValue]));
        	$details = $this->detail->getParent();
        	$details->add($labelValue, $this->detail);
        	$details->remove($this->label);
        }*/
        $this->doc->save();
    }

}