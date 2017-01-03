<?php

class DRMCrdAjoutForm extends acCouchdbForm 
{
    protected $_drm = null;
    protected $_config = null;

    public function __construct(DRM $drm, Configuration $config, $options = array(), $CSRFSecret = null) {
		$this->_drm = $drm;
        $this->_config = $config;
        $defaults = array(
        	'categorie' => 'T',
        	'type' => 'PERSONNALISEES',
        	'centilisation' => 'CL_75'
        );
        parent::__construct($drm, $defaults, $options, $CSRFSecret);
    }
    
    public function configure() 
    {
    	$categories = $this->getCategories();
    	$types = $this->getTypes();
    	$centilisations = $this->getCentilisations();
    	
        $this->setWidgets(array(
            'categorie' => new sfWidgetFormChoice(array('choices' => $categories)),
            'type' => new sfWidgetFormChoice(array('choices' => $types)),
            'centilisation' => new sfWidgetFormChoice(array('choices' => $centilisations)),
        	'disponible' => new sfWidgetFormInput(),
        	'centilitre' => new sfWidgetFormInputFloat(array('float_format' => "%01.01f")),
        	'bib' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => array(1 => "Oui", 0 => "Non"), 'renderer_options' => array('formatter' => array($this, 'formatter'))))
        ));
        $this->widgetSchema->setLabels(array(
            'categorie' => 'Catégorie fiscale : ',
            'type' => 'Type CRD : ',
            'centilisation' => 'Centilisation : ',
        	'disponible' => 'Stock disponible : ',
        	'centilitre' => 'Centilisation : ',
        	'bib' => 'Conditionnement BIB : '
        ));

        $this->setValidators(array(
            'categorie' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($categories))),
            'type' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($types))),
            'centilisation' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($centilisations))),
        	'disponible' => new sfValidatorInteger(array('required' => false)),
        	'centilitre' => new sfValidatorNumber(array('required' => false)),
        	'bib' => new sfValidatorChoice(array('required' => true, 'choices' => array(1, 0)))
        ));
		$this->setDefault('bib', 0);
        $this->validatorSchema->setPostValidator(new DRMCrdValidator(null, array('drm' => $this->_drm)));
        $this->widgetSchema->setNameFormat('crd[%s]');
    }
    
    public function getCategories()
    {
    	return $this->_config->crds->categorie->toArray();
    }
    
    public function getTypes()
    {
    	return $this->_config->crds->type->toArray();
    }
    
    public function getCentilisations()
    {
    	return $this->_config->crds->centilisation->toArray();
    }

    public function getDRM() {

        return $this->_drm;
    }

    public function addCrd() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }
        $stock = (isset($this->values['disponible']) && $this->values['disponible'])? $this->values['disponible'] : 0;
        $this->_drm->addCrd($this->values['categorie'], $this->values['type'], $this->values['centilisation'], $this->values['centilitre'], $this->values['bib'], $stock);
        return  $this->_drm;
    }

    public function formatter($widget, $inputs) {
		$result = '<ul class="radio_list">';
		foreach ($inputs as $k => $input) {
			$result .= '<li>' . $input ['input'] . '   ' . $input ['label'] . '</li>';
		}
		$result .= '</ul>';
		return $result;
	}

}