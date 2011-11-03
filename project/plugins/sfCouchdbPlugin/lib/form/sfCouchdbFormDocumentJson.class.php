<?php

abstract class sfCouchdbFormDocumentJson extends sfCouchdbForm {
    public function __construct(sfCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        try {
            $object->getCouchdbDocument();
        } catch (Exception $exc) {
            throw new sfCouchdbException(sprintf('The form only accepts an object with a associated document'));
        }

        $this->object = $object;
        $this->isNew = false;
        
        parent::__construct(array(), $options, $CSRFSecret);

        $this->updateDefaultsFromObject();
    }

    protected function doSave($con = null) {
        if (null === $con) {
            $con = $this->getConnection();
        }

        $this->updateObject();
        $this->object->getCouchdbDocument()->save();
    }

    public function getModelName() {
        return null;
    }

}
