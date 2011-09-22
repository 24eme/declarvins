<?php

class sfCouchdbJson implements IteratorAggregate, ArrayAccess, Countable {

    /**
     *
     * @var array
     */
    private $_fields = null;
    /**
     *
     * @var array 
     */
    private $_fields_name = null;
    /**
     *
     * @var bool
     */
    private $_is_array = false;
    /**
     *
     * @var string 
     */
    private $_definition_model = null;
    /**
     *
     * @var string
     */
    private $_definition_hash = null;
    /**
     *
     * @var sfCouchdbDocument 
     */
    private $_couchdb_document = null;
    /**
     *
     * @var string
     */
    private $_hash = null;
    private $_filter = null;
    private $_filter_persisent = false;

    /**
     *
     * @param sfCouchdbJsonDefinition $definition
     * @param sfCouchdbDocument $couchdb_document
     * @param string $hash 
     */
    public function __construct(sfCouchdbJsonDefinition $definition, $couchdb_document, $hash) {
        $this->_fields = array();
        $this->_fields_name = array();
        $this->_is_array = false;

        $this->_definition_model = $definition->getModel();
        $this->_definition_hash = $definition->getHash();

        $this->_couchdb_document = $couchdb_document;
        $this->_hash = $hash;
        $this->initializeDefinition();
    }

    /**
     * Retourne le document conteneur (permet donc de retourner à la racine)
     * @return sfCouchdbDocument 
     */
    public function getCouchdbDocument() {
        return $this->_couchdb_document;
    }

    /**
     * Ajoute les différents champs requis du modèle
     */
    private function initializeDefinition() {
        foreach ($this->getDefinition()->getRequiredFields() as $field_definition) {
            $this->_add($field_definition->getKey(), null);
        }
    }

    /**
     * Retourne la définition du modèle associé
     * 
     * @return sfCouchdbJsonDefinition 
     */
    public function getDefinition() {
        return sfCouchdbManager::getDefinitionByHash($this->_definition_model, $this->_definition_hash);
    }

    /**
     * Permet de passer l'objet en mode "Tableau", il possédera donc des clés numériques
     * 
     * @param bool $value 
     */
    public function setIsArray($value) {
        $this->_is_array = $value;
    }

    /**
     * Permet de savoir si l'objet est en mode tableau
     * @return bool 
     */
    public function isArray() {
        return $this->_is_array;
    }

    /**
     * Charge les données à partir de différents types : array, stdClass et sfCouchdbJson
     * @param mixed $data 
     */
    public function load($data) {
        if (!is_null($data)) {
            foreach ($data as $key => $item) {
                if (!$this->exist($key)) {
                    $this->_add($key);
                }
                $this->_set($key, $item);
            }
        }
    }

    protected function formatFieldKey($key) {
        return sfInflector::underscore($key);
    }

    /**
     *
     * @param string $key
     * @return mixed 
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     *
     * @param string $key
     * @return mixed 
     */
    protected function _get($key) {
        return $this->getField($key);
    }

    /**
     *
     * @param string $key_or_hash
     * @return mixed 
     */
    public function get($key_or_hash) {
        $obj_hash = new sfCouchdbHash($key_or_hash);
        if ($obj_hash->isAlone()) {
            if (!$this->isArray() && $this->hasAccessor($obj_hash->getFirst())) {
                $method = $this->getAccessor($obj_hash->getFirst());
                return $this->$method();
            }
            return $this->_get($obj_hash->getFirst());
        } else {
            return $this->get($obj_hash->getFirst())->get($obj_hash->getAllWithoutFirst());
        }
    }

    /**
     *
     * @param string $key
     * @param mixed $data_or_object
     * @return mixed 
     */
    private function setFromDataOrObject($key, $data_or_object) {
        if ($data_or_object instanceof sfCouchdbJson) {
            $field = $this->_get($key);
            $field->load($data_or_object->getData());
        } elseif ($data_or_object instanceof stdClass) {
            $field = $this->_get($key);
            $field->load($data_or_object);
        } elseif (is_array($data_or_object)) {
            $field = $this->_get($key);
            $field->load($data_or_object);
        } else {
            if (!$this->exist($key)) {
                throw new sfCouchdbException(sprintf('field inexistant : %s (%s%s)', $key, $this->_definition_model, $this->getHash()));
            }
            if ($this->isArray()) {
                $this->_fields[$key] = $data_or_object;
            } else {
                $this->_fields[$this->formatFieldKey($key)] = $data_or_object;
            }
            return $data_or_object;
        }

        return $field;
    }

    public function _set($key, $value) {
        return $this->setFromDataOrObject($key, $value);
    }

    public function set($key_or_hash, $value) {
        $obj_hash = new sfCouchdbHash($key_or_hash);
        if ($obj_hash->isAlone()) {
            if (!$this->isArray() && $this->hasMutator($obj_hash->getFirst())) {
                $method = $this->getMutator($obj_hash->getFirst());
                return $this->$method($value);
            }
            return $this->_set($obj_hash->getFirst(), $value);
        } else {
            return $this->get($obj_hash->getFirst())->set($obj_hash->getAllWithoutFirst(), $value);
        }
    }

    public function remove($key_or_hash) {
        $obj_hash = new sfCouchdbHash($key_or_hash);
        if ($obj_hash->isAlone()) {
            if ($this->_is_array) {
                return $this->removeNumeric($key_or_hash);
            } else {
                return $this->removeNormal($key_or_hash);
            }
        } else {
            return $this->getField($obj_hash->getFirst())->remove($obj_hash->getAllWithoutFirst());
        }
    }

    private function removeNormal($key) {
        if ($this->hasField($key)) {
            unset($this->_fields[$this->formatFieldKey($key)]);
            return true;
        }
        return false;
    }

    private function removeNumeric($key) {
        if ($this->hasField($key)) {
            unset($this->_fields[$key]);
            return true;
        }
        return false;
    }

    public function clear() {
        if ($this->_is_array) {
            foreach ($this->_fields as $key => $field) {
                $this->remove($key);
            }
        }
    }

    public function __set($key, $value) {
        return $this->set($key, $value);
    }

    public function add($key = null, $item = null) {
        return $this->_add($key, $item);
    }

    protected function _add($key = null, $item = null) {
        if (!$this->getDefinition()->exist($key)) {
            throw new sfCouchdbException(sprintf("Definition error : %s (%s)", $key, $this->getHash()));
        }
        if ($this->_is_array) {
            $ret = $this->addNumeric();
        } else {
            $ret = $this->addNormal($key);
        }
        if (!is_null($item)) {
            if ($this->_is_array) {
                $this->set(count($this->_fields) - 1, $item);
            } else {
                $this->set($key, $item);
            }
        }
        return $ret;
    }

    private function addNormal($key = null) {
        if ($this->hasField($key)) {
            return $this->getField($key);
        }
        $name = $key;
        $key = $this->formatFieldKey($key);
        // ajouter le hash et le document
        $field = $this->getDefinition()->get($key)->getDefaultValue($this->_couchdb_document, $this->_hash . '/' . $name);
        $this->_fields[$key] = $field;
        if ($this->getDefinition()->get($key)->isMultiple()) {
            $this->_fields_name[$key] = $name;
        }
        return $field;
    }

    private function addNumeric() {
        $field = $this->getDefinition()->get('*')->getDefaultValue($this->_couchdb_document, $this->_hash . '/' . count($this->_fields));
        //$field = $this->getDefinition()->getJsonField(null, true, $this->_couchdb_document, $this->_hash . '/' . count($this->_fields));
        $this->_fields[] = $field;

        return $field;
    }

    public function exist($key) {
        return $this->hasField($key);
    }

    protected function hasField($key) {
        if ($this->_is_array) {
            return $this->hasFieldNumeric($key);
        } else {
            return $this->hasFieldNormal($key);
        }
    }

    private function hasFieldNormal($key) {
        if (array_key_exists(strtolower($key), $this->_fields)) {
            return true;
        }
        return array_key_exists($this->formatFieldKey($key), $this->_fields);
    }

    private function hasFieldNumeric($key) {
        return array_key_exists($key, $this->_fields);
    }

    public function getFieldName($key) {
        if ($this->_is_array) {
            return $this->getFieldNameNumeric($key);
        } else {
            return $this->getFieldNameNormal($key);
        }
    }

    private function getFieldNameNormal($key) {
        if ($this->hasField($key)) {
            if ($this->getDefinition()->get($key)->isMultiple()) {
                return $this->_fields_name[$key];
            } else {
                return $this->getDefinition()->get($key)->getName();
            }
        } else {
            throw new sfCouchdbException(sprintf('field inexistant : %s (%s)', $key, $this->getHash()));
        }
    }

    private function getFieldNameNumeric($key) {
        if ($this->hasField($key)) {
            return $key;
        } else {
            throw new sfCouchdbException(sprintf('field inexistant : %s', $key));
        }
    }

    public function getFields() {
        return $this->_fields;
    }

    public function getField($key) {
        if ($this->_is_array) {
            return $this->getFieldNumeric($key);
        } else {
            return $this->getFieldNormal($key);
        }
    }

    private function getFieldNormal($key) {
        if ($this->hasField($key)) {
            return $this->_fields[$this->formatFieldKey($key)];
        } else {
            throw new sfCouchdbException(sprintf('field inexistant : %s (%s%s)', $key, $this->_definition_model, $this->getHash()));
        }
    }

    private function getFieldNumeric($key) {
        if ($this->hasField($key)) {
            return $this->_fields[$key];
        } else {
            throw new sfCouchdbException(sprintf('field inexistant : %s (%s%s)', $key, $this->_definition_model, $this->getHash()));
        }
    }

    public function __call($method, $arguments) {
        if (in_array($verb = substr($method, 0, 3), array('set', 'get'))) {
            $name = substr($method, 3);
            if ($this->exist($name)) {
                return call_user_func_array(
                        array($this, $verb), array_merge(array($name), $arguments)
                );
            } else {
                throw new sfCouchdbException(sprintf('Method undefined : %s', $method));
            }
        } else {
            throw new sfCouchdbException(sprintf('Method undefined : %s', $method));
        }
    }

    protected function getModelAccessor() {
        if (!isset(sfCouchdbManager::getInstance()->_custom_accessors[$this->_definition_model][$this->_definition_hash])) {
            sfCouchdbManager::getInstance()->_custom_accessors[$this->_definition_model][$this->_definition_hash] = array();
        }
        return sfCouchdbManager::getInstance()->_custom_accessors[$this->_definition_model][$this->_definition_hash];
    }

    protected function getModelMutator() {
        if (!isset(sfCouchdbManager::getInstance()->_custom_mutators[$this->_definition_model][$this->_definition_hash])) {
            sfCouchdbManager::getInstance()->_custom_mutators[$this->_definition_model][$this->_definition_hash] = array();
        }
        return sfCouchdbManager::getInstance()->_custom_mutators[$this->_definition_model][$this->_definition_hash];
    }

    protected function hasAccessor($key) {
        $fieldName = $this->formatFieldKey($key);
        $model_accessor = $this->getModelAccessor();

        if (array_key_exists($fieldName, $model_accessor) && is_null($model_accessor[$fieldName])) {
            return false;
        } elseif (array_key_exists($fieldName, $model_accessor) && !is_null($model_accessor[$fieldName])) {
            return $model_accessor[$fieldName];
        } else {
            $accessor = 'get' . sfInflector::camelize($fieldName);
            if ($accessor != 'get' && method_exists($this, $accessor)) {
                sfCouchdbManager::getInstance()->_custom_accessors[$this->_definition_model][$this->_definition_hash][$fieldName] = $accessor;
                return $accessor;
            } else {
                sfCouchdbManager::getInstance()->_custom_accessors[$this->_definition_model][$this->_definition_hash][$fieldName] = null;
                return false;
            }
        }
    }

    public function getAccessor($key) {
        $accessor = $this->hasAccessor($key);
        if ($accessor) {
            return $accessor;
        }
        return null;
    }

    protected function hasMutator($key) {
        $fieldName = $this->formatFieldKey($key);
        $model_mutator = $this->getModelMutator();
        if (array_key_exists($fieldName, $model_mutator) && is_null($model_mutator[$fieldName])) {
            return false;
        } elseif (array_key_exists($fieldName, $model_mutator) && !is_null($model_mutator[$fieldName])) {
            return $model_mutator[$fieldName];
        } else {
            $mutator = 'set' . sfInflector::camelize($fieldName);
            if ($mutator != 'set' && method_exists($this, $mutator)) {
                sfCouchdbManager::getInstance()->_custom_mutators[$this->_definition_model][$this->_definition_hash][$fieldName] = $mutator;
                return $mutator;
            } else {
                sfCouchdbManager::getInstance()->_custom_mutators[$this->_definition_model][$this->_definition_hash][$fieldName] = null;
                return false;
            }
        }
    }

    protected function getMutator($key) {
        $mutator = $this->hasMutator($key);
        if ($mutator) {
            return $mutator;
        }
        return null;
    }

    public function getData() {
        $data = array();
        foreach ($this->_fields as $key => $field) {
            if ($this->_is_array) {
               if ($this->getDefinition()->get($key)->isCollection()) {
                    $data[] = $field->getData();
                } else {
                    $data[] = $field;
                } 
            } else {
                if ($this->getDefinition()->get($key)->isCollection()) {
                    $data[$this->getFieldName($key)] = $field->getData();
                } else {
                    $data[$this->getFieldName($key)] = $field;
                }
            }
        }

        if ($this->_is_array) {
            return $data;
        } else {
            return (Object) $data;
        }
    }

    public function fromArray($values) {
        foreach ($values as $key => $value) {
            if (!is_array($value)) {
            	if($this->exist($key)) {
                	$this->set($key, $value);
            	}
            }
        }
    }

    public function toSimpleFields() {
        $simple_fields = array();
        foreach ($this->_fields as $key => $field) {
            if (!$this->getDefinition()->get($key)->isCollection()) {
                $simple_fields[$key] = $field;
            }
        }
        return $simple_fields;
    }

    public function toArray($deep = 1) {
        $array_fields = array();
	foreach ($this as $key => $field) {
	  if ($deep > 1 && $this->getDefinition()->get($key)->isCollection()) {
	    $array_fields[$key] = $field->toArray($deep);
	    continue;
	  }
	  $array_fields[$key] = $this->get($key);
        }
        return $array_fields;
    }

    protected function definitionValidation() {
        foreach ($this->_fields as $key => $field) {
            if (!$this->getDefinition()->get($key)->isValid($field)) {
                throw new sfCouchdbException(sprintf("Value not valid : %s required %s (%s)", gettype($field), $this->getDefinition()->get($key)->getType(), $this->getHash() . "/" . $key));
            }
            if ($this->getDefinition()->get($key)->isCollection()) {
                $field->definitionValidation();
            }
        }
    }

    public function getParentHash() {
        return preg_replace('/\/[^\/]+$/', '', $this->getHash());
    }

    public function getParent() {
        return $this->getCouchdbDocument()->get($this->getParentHash());
    }

    public function getKey() {
        return preg_replace('/^.*\//', '\1', $this->getHash());
    }

    public function getHash() {
        return $this->_hash;
    }

    public function getFirst() {
        return $this->getIterator()->getFirst();
    }

    public function getFirstKey() {
        return $this->getIterator()->getFirstKey();
    }

    public function getLast() {
        return $this->getIterator()->getLast();
    }

    public function getLastKey() {
        return $this->getIterator()->getLastKey();
    }

    protected function update($params = array()) {
        foreach ($this->_fields as $key => $field) {
            if ($this->getDefinition()->get($key)->isCollection()) {
                $field->update($params);
            }
        }
    }

    public function filter($expression, $persisent = false) {
        $this->_filter = $expression;
        $this->_filter_persisent = $persisent;
        return $this;
    }

    public function clearFilter() {
        $this->_filter = null;
        $this->_filter_persisent = false;
    }

    public function getIterator() {
        $iterator = new sfCouchdbJsonArrayIterator($this, $this->_filter);
        if (!$this->_filter_persisent) {
            $this->clearFilter();
        }
        return $iterator;
    }

    public function offsetGet($index) {
        return $this->get($index);
    }

    public function offsetSet($index, $newval) {
        return $this->set($index, $newval);
    }

    public function offsetExists($index) {
        return $this->hasField($index);
    }

    public function offsetUnset($offset) {
        return $this->remove($offset);
    }

    public function count() {
        return $this->getIterator()->count();
    }

}
