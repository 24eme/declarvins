<?php

/* This file is part of the acCouchdbPlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acCouchdbClient model.
 * 
 * @package    acCouchdbPlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */

class acCouchdbClient extends couchClient {
    
    const HYDRATE_ON_DEMAND = 1;
    const HYDRATE_ON_DEMAND_WITH_DATA = 2;
    const HYDRATE_JSON = 3;
    const HYDRATE_ARRAY = 4;
    const HYDRATE_DOCUMENT = 5;

    /**
     * Store an acCouchdbDocument in the database
     * 
     * @param acCouchdbDocument $document An acCouchdbDocoument class
     * @return void
     */
    public function save($document) {
        $method = 'POST';
        $url = '/' . urlencode($this->dbname);
        if (!$document->isNew()) {
            $method = 'PUT';
            $url.='/' . urlencode($document->get('_id'));
        }
        return $this->_queryAndTest($method, $url, array(200, 201), array(), $document->getData());
    }

    /**
     * Store an acCouchdbDocument in the database
     * 
     * @param acCouchdbDocument $document An acCouchdbDocoument class
     * @return void
     * @deprecated
     * @see method save()
     */
    public function saveDocument($document) {
        $method = 'POST';
        $url = '/' . urlencode($this->dbname);
        if (!$document->isNew()) {
            $method = 'PUT';
            $url.='/' . urlencode($document->get('_id'));
        }
        return $this->_queryAndTest($method, $url, array(200, 201), array(), $document->getData());
    }

    /**
     * Remove an acCouchdbDocument from the database
     * 
     * @param acCouchdbDocument $document An acCouchdbDocoument class
     * @return mixed
     */
    public function delete($document) {

        return $this->deleteDoc($document->getData());
    }

    /**
     * Remove an acCouchdbDocument from the database
     * 
     * @param acCouchdbDocument $document An acCouchdbDocoument class
     * @return mixed 
     * @deprecated
     * @see method delete()
     */
    public function deleteDocument($document) {

        return $this->deleteDoc($document->getData());
    }

    /**
     * Fetch an acCouchdbDocument from database
     * 
     * @param string $id The couchdb document _id
     * @param int $hydrate Hydration mode: see acCouchdbClient::HYDRATE_* constants
     * @return acCouchdbDocument|stdClass|array Depending on hydration mode. null if no result.
     */
    public function find($id, $hydrate = self::HYDRATE_DOCUMENT) {
        try {
            if ($hydrate == self::HYDRATE_DOCUMENT) {
                $data = $this->getDoc($id);

                return $this->create($data);
            } elseif ($hydrate == self::HYDRATE_JSON) {

                return $this->getDoc($id);
            } elseif ($hydrate == self::HYDRATE_ARRAY) {

                return $this->asArray()->getDoc($id);
            } else {
                
                throw new acCouchdbException('This hydration method does not exist');
            }
        } catch (couchException $exc) {

            return null;
        }
    }

    /**
     * Fetch an acCouchdbDocument from database
     * 
     * @param string $id The couchdb document _id
     * @param int $hydrate Hydration mode: see acCouchdbClient::HYDRATE_* constants
     * @return acCouchdbDocument|stdClass|array Depending on hydration mode. null if no result.
     * @deprecated
     * @see method find()
     */
    public function retrieveDocumentById($id, $hydrate = self::HYDRATE_DOCUMENT) {
        try {
            if ($hydrate == self::HYDRATE_DOCUMENT) {
                $data = $this->getDoc($id);

                return $this->createDocumentFromData($data);
            } elseif ($hydrate == self::HYDRATE_JSON) {

                return $this->getDoc($id);
            } elseif ($hydrate == self::HYDRATE_ARRAY) {

                return $this->asArray()->getDoc($id);
            } else {
                
                throw new acCouchdbException('This hydrate method does not exist');
            }
        } catch (couchException $exc) {

            return null;
        }
    }

    /**
     * Transform a couchdb document to an acCouchdbDocument class
     * 
     * @param stdClass $data The json data of couchdb document
     * @return acCouchdbDocument
     */
    public function create($data) {
        if (!isset($data->type)) {
            
            throw new acCouchdbException('Property "type" ($data->type)');
        }
        if (!class_exists($data->type)) {
            
            throw new acCouchdbException('Class "' . $data->type . '" not found');
        }
        
        $doc = new $data->type();
        $doc->loadFromCouchdb($data);
        
        return $doc;
    }

    /**
     * Transform a couchdb document to an acCouchdbDocument class
     * 
     * @param stdClass $data The json data of couchdb document
     * @return acCouchdbDocument
     * @deprecated
     * @see method create()
     */
    public function createDocumentFromData($data) {
        if (!isset($data->type)) {
            throw new acCouchdbException('data should have a type');
        }
        if (!class_exists($data->type)) {
            throw new acCouchdbException('class ' . $data->type . ' not found');
        }
        $doc = new $data->type();
        $doc->loadFromCouchdb($data);
        return $doc;
    }

    /**
     * Fecth a collection of couchdb document from a view
     * 
     * @param string $id The couchdb view document _id
     * @param string $name The name of the view
     * @param int $hydrate Hydration mode: see acCouchdbClient::HYDRATE_* constants
     * @return acCouchdbValueCollection Collection of the view results
     */
    public function executeView($id, $name, $hydrate = self::HYDRATE_DOCUMENT) {
        if ($hydrate != self::HYDRATE_ON_DEMAND) {
            $this->include_docs(true);
        }
        if ($hydrate == self::HYDRATE_ARRAY) {
            $this->asArray();
        }
        return new acCouchdbValueCollection($this->getView($id, $name), $hydrate);
    }

    /**
     * Fecth a collection of couchdb documents
     * 
     * @param int $hydrate Hydration mode: see acCouchdbClient::HYDRATE_* constants
     * @return acCouchdbValueCollection Collection class of couchdb documents 
     */
    public function execute($hydrate = self::HYDRATE_DOCUMENT) {
        if ($hydrate != self::HYDRATE_ON_DEMAND) {
            $this->include_docs(true);
        }
        if ($hydrate == self::HYDRATE_ARRAY) {
            $this->asArray();
        }
        
        return new acCouchdbDocumentCollection($this->getAllDocs(), $hydrate);
    }

}
