<?php

class DRMDeclaratifRnaCollectionForm extends BaseForm implements FormBindableInterface
{
        public $virgin_object = null;
        protected $_rna;
        
    	public function __construct($rna, $options = array(), $CSRFSecret = null) {
        	$this->_rna = $rna;
        	parent::__construct(array(), $options, $CSRFSecret);
    	}

        public function configure()
        {
                if (count($this->_rna) == 0) {
                        $this->virgin_object = $this->_rna->add();
                }
                foreach ($this->_rna as $key => $object) {
                	if (!$key) {
                		$key = uniqid();
                	}
                    $this->embedForm ($key, new DRMDeclaratifRnaForm($object));
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
                        $this->embedForm($key, new DRMDeclaratifRnaForm($this->_rna->add()));
                }
        }

        public function unEmbedForm($key)
        {
                unset($this->widgetSchema[$key]);
                unset($this->validatorSchema[$key]);
                unset($this->embeddedForms[$key]);
                $this->_rna->remove($key);
        }

        public function offsetUnset($offset)
        {
                parent::offsetUnset($offset);
                if (!is_null($this->virgin_object)) {
                        $this->virgin_object->delete();
                }
    	}
}
