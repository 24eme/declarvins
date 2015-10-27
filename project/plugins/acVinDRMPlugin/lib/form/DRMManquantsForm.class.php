<?php
class DRMManquantsForm extends acCouchdbObjectForm
{   
    private $drm = null;


    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->drm = $object->getDocument();
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function configure()
  	{
      	$this->setWidgets(array(
        	'igp' => new WidgetFormInputCheckbox(),
      		'contrats' => new WidgetFormInputCheckbox(),
		));
		$this->widgetSchema->setLabels(array(
        	'igp' => 'Produit(s) IGP manquant(s)',
			'contrats' => 'Contrat(s) manquant(s)',
        ));
		$this->setValidators(array(
        	'igp' => new ValidatorBoolean(array('required' => false)),
			'contrats' => new ValidatorBoolean(array('required' => false)),
        ));
	    $this->widgetSchema->setNameFormat('drm_manquants[%s]');
  	}
        
    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        if($this->drm->hasVolumeVracWithoutDetailVrac()){
            $defaults['contrats'] = 1;
        }
        
       $this->setDefaults($defaults);     
    }
}