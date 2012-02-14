<?php

abstract class acCouchdbDocument extends acCouchdbDocumentStorable {

    protected $_is_new = true;
    protected $_loaded_data = null;

    public function loadFromCouchdb(stdClass $data) {
        if (!is_null($this->_loaded_data)) {
            throw new acCouchdbException("data already load");
        }
	if (isset($data->_attachments))
	  unset($data->_attachments);
        $this->_loaded_data = serialize($data);
        $this->load($data);
    }

    public function __toString() {
        return $this->get('_id') . '/' . $this->get('_rev');
    }

    public function __construct() {
        parent::__construct(acCouchdbManager::getDefinitionByHash($this->getDocumentDefinitionModel(), '/'), $this, "");
        $this->type = $this->getDefinition()->getModel();

        if (!$this->type) {
            throw new acCouchdbException('Model should include Type field in the document root');
        }
    }

    public function isNew() {
        if (!$this->_exist('_rev'))
            return true;
        return is_null($this->get('_rev'));
    }

    public function save() {
        if ($this->isNew() && !$this->get('_id')) {
            $this->constructId();
        }
        
        $this->definitionValidation();
        if ($this->isModified()) {
            $ret = acCouchdbManager::getClient()->save($this);
            $this->_rev = $ret->rev;
            $this->_loaded_data = serialize($this->getData());
            return $ret;
        }
        return false;
    }
    
    public function constructId() {
        
    }

    public function getData() {
        $data = parent::getData();
        if ($this->isNew()) {
            unset($data->_rev);
        }
        return $data;
    }

    public function getDocumentDefinitionModel() {
        throw new acCouchdbException('Definition model not implemented');
    }

    public function delete() {
        return acCouchdbManager::getClient()->delete($this);
    }

    public function storeAttachment($file, $content_type = 'application/octet-stream', $filename = null) { 
      return acCouchdbManager::getClient()->storeAttachment($this, $file, $content_type, $filename);
   }

    public function getAttachmentUri($filename) {
      return 'http://localhost:5984'.acCouchdbManager::getClient()->getAttachmentUri($this, $filename);
    }

    public function update($params = array()) {
        return parent::update($params);
    }

    public function loadAllData() {
        return parent::loadAllData();
    }

    public function isModified() {
        return $this->isNew() || (unserialize($this->_loaded_data) != $this->getData());
    }

    public function __clone() {
        $this->_rev = null;
        $this->_id = null;
    }

}
