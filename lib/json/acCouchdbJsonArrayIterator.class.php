<?php
class acCouchdbJsonArrayIterator extends ArrayIterator {
    private $_json;

    public function __construct(acCouchdbJson $json, $filter = null)
    {
        $this->_json = $json;
        if (!is_null($filter)) {
            $fields = array();
            foreach($json->getFields() as $key => $field) {
                if (preg_match('/'.$filter.'/', $key)) {
                    $fields[$key] = null;
                }
            }
        } else {
            $fields = array_fill_keys(array_keys($json->getFields()), null);
        }
        parent::__construct($fields);
    }

    public function getFirst() {
        if($this->valid()){
            $this->seek(0);
            return $this->current();
        } else {
            throw new acCouchdbException('This iterator has no entrie');
        }
    }

    public function getFirstKey() {
        if($this->valid()){
            $this->seek(0);
            return $this->key();
        } else {
            throw new acCouchdbException('This iterator has no entrie');
        }
    }

    public function getLast() {
        if($this->valid()){
            $this->seek($this->count() -1);
            return $this->current();
        } else {
            throw new acCouchdbException('This iterator has no entrie');
        }
    }

    public function getLastKey() {
        if($this->valid()){
            $this->seek($this->count() -1);
            return $this->key();
        } else {
            throw new acCouchdbException('This iterator has no entrie');
        }
    }

    public function current() {
        return $this->_json->get($this->key());
    }

    public function key() {
        return $this->_json->getFieldName(parent::key());
    }
 
    public function offsetGet($index) {
        return $this->_json->offsetGet($index);
    }

    public function offsetSet($index, $newval) {
        return $this->_json->offsetSet($index, $newval);
    }

    public function  offsetExists($index) {
        return $this->_json->offsetExists($index);
    }

    public function offsetUnset($index) {
        return $this->_json->offsetUnset($index);
    }
}
