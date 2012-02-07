<?php

abstract class acCouchdbDocumentTree extends acCouchdbJson {
   protected $_root_class_name = null;
   protected $_tree_class_name = null;
   protected $_is_new = false;
   protected $_storage = array();

   public function  __construct($definition, $_couchdb_document, $hash) {
        $this->configureTree();
        parent::__construct($definition, $_couchdb_document, $hash);
   }

   abstract public function configureTree();

   public function getRootClassName() {
       if (!class_exists($this->_root_class_name)) {
            throw new acCouchdbException("Root class name don't exist");
       } else {
           return $this->_root_class_name;
       }
   }

   public function getTreeClassName() {
       if (!class_exists($this->_tree_class_name)) {
            throw new acCouchdbException("Tree class name don't exist");
       } else {
           return $this->_tree_class_name;
       }
   }

   public function isNew() {
       return $this->_is_new;
   }

   protected function store($key, $function, $arguments = array()) {
        if (!array_key_exists($key, $this->_storage)) {
            $this->_storage[$key] = call_user_func_array($function, $arguments);
        }
        return $this->_storage[$key];
   }

   protected function update($params = array()) {
        $this->_storage = array();
        parent::update($params);
    }
}
