<?php

class sfCouchdbDocumentCollection implements IteratorAggregate, ArrayAccess, Countable {

    protected $_docs = array();
    protected $_hydrate = array();
    protected $_class = null;

    public function __construct($data = null, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        $this->_hydrate = $hydrate;
        $this->load($data);
    }

    protected function load($data) {
        if (!is_null($data)) {
            try {
                if ($this->_hydrate == sfCouchdbClient::HYDRATE_ARRAY) {
                    foreach ($data["rows"] as $item) {
                        $this->_docs[$item['id']] = $item["doc"];
                    }
                } else {
                    foreach ($data->rows as $item) {
                        if ($this->_hydrate == sfCouchdbClient::HYDRATE_ON_DEMAND) {
                            $this->_docs[$item->id] = null;
                        } elseif ($this->_hydrate == sfCouchdbClient::HYDRATE_ON_DEMAND_WITH_DATA) {
                            $this->_docs[$item->id] = $item->doc;
                        } elseif ($this->_hydrate == sfCouchdbClient::HYDRATE_JSON) {
                            $this->_docs[$item->id] = $item->doc;
                        } elseif ($this->_hydrate == sfCouchdbClient::HYDRATE_DOCUMENT) {
                            $this->_docs[$item->id] = sfCouchdbManager::getClient()->createDocumentFromData($item->doc);
                        }
                    }
                }
            } catch (Exception $exc) {
                throw new sfCouchdbException('Load error : data invalid');
            }
        }
    }
    
    public function getIds() {
        return array_keys($this->_docs);
    }

    public function getDocs() {
        return $this->_docs;
    }

    public function getIterator() {
        return new sfCouchdbDocumentCollectionArrayIterator($this);
    }

    public function get($id) {
        if ($this->contains($id)) {
            if ($this->_hydrate == sfCouchdbClient::HYDRATE_ON_DEMAND_WITH_DATA && !($this->_docs[$id] instanceof sfCouchdbDocument)) {
                $this->_docs[$id] = sfCouchdbManager::getClient()->createDocumentFromData($this->_docs[$id]);
            }
            if ($this->_hydrate == sfCouchdbClient::HYDRATE_ON_DEMAND && is_null($this->_docs[$id])) {
                $this->_docs[$id] = sfCouchdbManager::getClient()->retrieveDocumentById($id);
            }
            return $this->_docs[$id];
        } else {
            throw new sfCouchdbException('This collection does not contains this id');
        }
    }

    public function contains($id) {
        return array_key_exists($id, $this->_docs);
    }

    public function remove($id) {
        if ($this->contains($id)) {
            unset($this->_docs[$id]);
            return true;
        } else {
            return false;
        }
    }

    public function offsetGet($index) {
        return $this->get($index);
    }

    public function offsetSet($index, $newval) {
        throw new sfCouchdbException('Do not set a document use add');
    }

    public function offsetExists($index) {
        return $this->contains($index);
    }

    public function offsetUnset($offset) {
        return $this->remove($offset);
    }

    public function count() {
        return count($this->_docs);
    }

}