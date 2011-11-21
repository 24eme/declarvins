<?php

class sfCouchdbJson extends acCouchdbJson {
    
}

class acCouchdbJson extends acCouchdbJsonField implements IteratorAggregate, ArrayAccess, Countable {

    /**
     *
     * @var string
     */
    private $_filter = null;
    private $_filter_persisent = false;

    /**
     *
     * @param acCouchdbJsonDefinition $definition
     * @param acCouchdbDocument $document
     * @param string $hash 
     */
    public function __construct(acCouchdbJsonDefinition $definition, acCouchdbDocument $document, $hash) {
        parent::__construct($definition, $hash);
    }

    /**
     *
     * @param string $key_or_hash
     * @return mixed 
     */
    public function get($key_or_hash) {
        $obj_hash = new acCouchdbHash($key_or_hash);
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
     * @return mixed 
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     *
     * @param string $key_or_hash
     * @return mixed 
     */
    public function getOrAdd($key_or_hash) {
        $obj_hash = new acCouchdbHash($key_or_hash);
        if ($obj_hash->isAlone()) {
            return $this->add($obj_hash->getFirst());
        }
        return $this->add($obj_hash->getFirst())->getOrAdd($obj_hash->getAllWithoutFirst());
    }

    public function set($key_or_hash, $value) {
        $obj_hash = new acCouchdbHash($key_or_hash);
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

    public function __set($key, $value) {
        return $this->set($key, $value);
    }

    public function remove($key_or_hash) {
        return $this->_remove($key_or_hash);
    }

    public function clear() {
        if ($this->_is_array) {
            foreach ($this->_fields as $key => $field) {
                $this->_remove($key);
            }
        }
    }

    public function add($key = null, $item = null) {
        return $this->_add($key, $item);
    }

    public function exist($key) {
        return $this->_exist($key);
    }

    public function __call($method, $arguments) {
        if (in_array($verb = substr($method, 0, 3), array('set', 'get'))) {
            $name = substr($method, 3);
            if ($this->exist($name)) {
                return call_user_func_array(
                        array($this, $verb), array_merge(array($name), $arguments)
                );
            } else {
                throw new acCouchdbException(sprintf('Method undefined : %s', $method));
            }
        } else {
            throw new acCouchdbException(sprintf('Method undefined : %s', $method));
        }
    }

    public function toJson() {
        return $this->getData();
    }

    public function fromArray($values) {
        foreach ($values as $key => $value) {
            if (!is_array($value)) {
                if ($this->exist($key)) {
                    $this->set($key, $value);
                }
            }
        }
    }

    /**
     * @deprecated
     * @see call function toArray(false, false)
     */
    public function toSimpleFields() {
        return $this->toArray(false, false);
    }

    public function toArray($deep_array = true, $fetch_object = true) {
        $array_fields = array();
        foreach ($this as $key => $field) {
            if ($deep_array && $this->fieldIsCollection($key)) {
                $array_fields[$key] = $field->toArray($deep);
                continue;
            } elseif ($deep_object && $this->fieldIsCollection($key)) {
                $array_fields[$key] = $this->get($key);
            } elseif (!$this->fieldIsCollection($key)) {
                $array_fields[$key] = $this->get($key);
            }
        }

        return $array_fields;
    }

    public function getParentHash() {
        return preg_replace('/\/[^\/]+$/', '', $this->getHash());
    }

    public function getParent() {
        return $this->getDocument()->get($this->getParentHash());
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
        foreach ($this as $key => $field) {
            if ($this->fieldIsCollection($key)) {
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
        $iterator = new acCouchdbJsonArrayIterator($this, $this->_filter);
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
