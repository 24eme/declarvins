<?php
class DRMESCollectionDetailCrdForm extends BaseForm implements FormBindableInterface {

    public $virgin_object = null;
    protected $details;
    
    public function __construct($details, $options = array(), $CSRFSecret = null) {
        $this->details = $details;
        parent::__construct(array(), $options, $CSRFSecret);
    }
    
        public function configure()
        {
                if (count($this->details) == 0) {
                        $this->virgin_object = $this->details->add();
                }
                foreach ($this->details as $key => $object) {
                	if (!$key) {
                		$key = uniqid();
                	}
                    $this->embedForm ($key, new DRMESDetailCrdForm($object));
                }
    }



        public function bind(array $taintedValues = null, array $taintedFiles = null)
        {
                foreach ($this->embeddedForms as $key => $form) {
                        if(!array_key_exists($key, $taintedValues)) {
                                $this->unEmbedForm($key);
                        }
                }
                foreach($taintedValues as $key => $values) {
                        if(!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                                continue;
                        }
                        $this->embedForm($key, new DRMESDetailCrdForm($this->details->add()));
                }
        }

        public function unEmbedForm($key)
        {
                unset($this->widgetSchema[$key]);
                unset($this->validatorSchema[$key]);
                unset($this->embeddedForms[$key]);
                $this->details->remove($key);
        }

        public function offsetUnset($offset)
        {
                parent::offsetUnset($offset);
                if (!is_null($this->virgin_object)) {
                        $this->virgin_object->delete();
                }
    	}
}