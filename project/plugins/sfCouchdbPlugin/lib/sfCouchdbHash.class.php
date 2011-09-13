<?php
    class sfCouchdbHash {
        private $_hash = array();
        public function  __construct($hash) {
            $tab_hash = explode('/', $hash);
            $this->_hash = array();
            foreach($tab_hash as $item) {
                if (trim($item) != '') {
                    $this->_hash[] = $item;
                }
            }
        }

        public function isAlone() {
            return (!(count($this->_hash) > 1));
        }
        
        public function isEmpty() {
            return (!(count($this->_hash) > 0));
        }
        
        public function getFirst() {
            if (count($this->_hash) > 0) {
                return $this->_hash[0];
            } else {
                return null;
            }
        }

        public function getAllWithoutFirst() {
            if (!$this->isAlone()) {
                $result = '';
                $separator = '';
                for($i = 1; $i < count($this->_hash); $i++) {
                    $result .= $separator.(string)$this->_hash[$i];
                    $separator = '/';
                }
                return $result;
            } else {
                return null;
            }
        }
    }