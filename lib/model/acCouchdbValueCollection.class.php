<?php

class sfCouchdbValueCollection extends acCouchdbValueCollection {
    
}

class acCouchdbValueCollection extends acCouchdbCollection {

    protected function load($data) {
        if (!is_null($data)) {
            try {
                if ($this->_hydrate == acCouchdbClient::HYDRATE_ARRAY) {
                    foreach ($data["rows"] as $item) {
                        $this->_datas[$item['id']] = $item["doc"];
                    }
                } else {
                    foreach ($data->rows as $item) {
                        if ($this->_hydrate == acCouchdbClient::HYDRATE_JSON) {
                            $this->_datas[$item->id] = $item->value;
                        }
                    }
                }
            } catch (Exception $exc) {
                throw new acCouchdbException('Load error : data invalid');
            }
        }
    }

    public function getValues() {
        return $this->getDatas();
    }

}