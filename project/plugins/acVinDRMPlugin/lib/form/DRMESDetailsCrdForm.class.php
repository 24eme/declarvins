<?php
class DRMESDetailsCrdForm extends acCouchdbForm {
    protected $obj;
    

    public function __construct($obj, $options = array(), $CSRFSecret = null) {
        $this->obj = $obj;
        parent::__construct($obj->getDocument(), array(), $options, $CSRFSecret);
    }
	
	public function configure() {
	    $details = new DRMESCollectionDetailCrdForm($this->getObject()->crd_details);
	    $this->embedForm('details', $details);
		$this->widgetSchema->setNameFormat('drm_es_detail_crd[%s]');
	}

    public function getFormTemplateDetails() {
        $form_embed = new DRMESDetailCrdForm($this->getObject()->crd_details->add());
        $form = new DRMDetailsCollectionTemplateForm($this, $form_embed, 'var---nbItem---');

        return $form->getFormTemplate();
    }
    
    public function getObject() {
    	return $this->obj;
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
            if($form instanceof FormBindableInterface) {
                $form->bind($taintedValues[$key], $taintedFiles[$key]);
                $this->updateEmbedForm($key, $form);
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }
    
    
    
    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }
    
    public function save() {
        $values = $this->getValues();
        if ($details = $values['details']) {
            foreach ($details as $doc) {
                if ($doc['volume'] && $doc['mois'] && $doc['annee']) {
                    var_dump($doc);
                    $detail = $this->obj->crd_details->getOrAdd($doc['annee'].$doc['mois']);
                    $detail->volume = $doc['volume'];
                    $detail->mois = $doc['mois'];
                    $detail->annee = $doc['annee'];
                }
            }
            //$this->obj->getDocument()->save();
        }exit;
    }
}