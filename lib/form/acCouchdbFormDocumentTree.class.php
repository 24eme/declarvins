<?php

abstract class acCouchdbFormJson extends acCouchdbForm {
    protected $doc = null;
    
    public function __construct(acCouchdbDocument $doc, $object, $options = array(), $CSRFSecret = null) {
        $this->doc = $doc;

        $class = $this->getModelName();
        if (!$object) {
            $this->object = new $class();
        } else {

             if (!$object instanceof $class) {
              throw new sfException(sprintf('The "%s" form only accepts a "%s" object.', get_class($this), $class));
             } 

            $this->object = $object;
            $this->isNew = $this->getObject()->isNew();
        }

        parent::__construct(array(), $options, $CSRFSecret);

        $this->updateDefaultsFromObject();
    }

    protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();
        $this->object->save($this->doc);
        
        // embedded forms
        $this->saveEmbeddedForms($con);

        $this->doc->save();
    }

}
