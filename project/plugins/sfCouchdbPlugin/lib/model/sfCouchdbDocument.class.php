<?php

abstract class sfCouchdbDocument extends sfCouchdbJson {

    protected $_is_new = true;
    protected $_loaded_data = null;

    public function loadFromCouchdb(stdClass $data) {
        if (!is_null($this->_loaded_data)) {
            throw new sfCouchdbException("data already load");
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
        parent::__construct(sfCouchdbManager::getDefinitionByHash($this->getDocumentDefinitionModel(), '/'), $this, "");
        $this->type = $this->getDefinition()->getModel();

        if (!$this->type) {
            throw new sfCouchdbException('Model should include Type field in the document root');
        }
    }

    public function isNew() {
        if (!$this->hasField('_rev'))
            return true;
        return is_null($this->get('_rev'));
    }

    public function save() {
        if ($this->isNew() && !$this->get('_id')) {
            $this->constructId();
        }
        
        $this->definitionValidation();
        if ($this->isModified()) {
            $ret = sfCouchdbManager::getClient()->saveDocument($this);
            $this->_rev = $ret->rev;
            $this->_loaded_data = serialize($this->getData());
            return $ret;
        }
        return false;
    }
    
    public function generateId() {
        
    }

    public function getData() {
        $data = parent::getData();
        if ($this->isNew()) {
            unset($data->_rev);
        }
        return $data;
    }

    public function getDocumentDefinitionModel() {
        throw new sfCouchdbException('Definition model not implemented');
    }

    public function delete() {
        return sfCouchdbManager::getClient()->deleteDocument($this);
    }

    public function storeAttachment($file, $content_type = 'application/octet-stream', $filename = null) { 
      return sfCouchdbManager::getClient()->storeAttachment($this, $file, $content_type, $filename);
   }

    public function getAttachmentUri($filename) {
      return 'http://localhost:5984'.sfCouchdbManager::getClient()->getAttachmentUri($this, $filename);
    }

    public function update($params = array()) {
        return parent::update($params);
    }

    public function isModified() {
        return $this->isNew() || (unserialize($this->_loaded_data) != $this->getData());
    }

    public function __clone() {
        $this->_rev = null;
        $this->_id = null;
    }

}
