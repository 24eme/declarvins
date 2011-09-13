<?php

class sfCouchdbManager {
    protected static $_instance;

    protected $_client = null;
    protected $_clients_model = array();

    protected $_definition = array();
    protected $_definition_tree_hash = array();
    protected $_definition_hash = array();

    public $_custom_accessors = array();
    public $_custom_mutators = array();

    protected $_schema = null;
    
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function initializeClient($dsn, $dbname) {
        self::getInstance()->_client = new sfCouchdbClient($dsn, $dbname);
	if (!self::getInstance()->_client->databaseExists())
	  throw new Exception($dbname." does not exist");
	return self::getInstance()->_client;
    }

    /**
     *
     * @param string $model
     * @return sfCouchdbClient 
     */
    public static function getClient($model = null) {
        if (is_null($model)) {
            return self::getInstance()->_client;
        } else {
            return self::getInstance()->getClientByModel($model);
        }
    }

    protected function getClientByModel($model) {
        if (!isset(self::getInstance()->_clients_model[$model])) {
            $class_name = $model.'Client';
            if (class_exists($class_name)) {
                self::getInstance()->_clients_model[$model] = new $class_name(self::getClient()->dsn(), self::getClient()->getDatabaseName());
            } else {
                throw new sfCouchdbException(sprintf("This model client doesn't exist : %s", $class_name));
            }
        }

        return self::getInstance()->_clients_model[$model];
    }
    
    public static function getSchema() {
        if (is_null(self::getInstance()->_schema)) {
            self::getInstance()->_schema = sfYaml::load(sfConfig::get('sf_config_dir').'/couchdb/schema.yml');
        }

        return self::getInstance()->_schema;
    }

    public static function setSchema($schema) {
        self::getInstance()->_schema = $schema;
    }

    public static function getDefinition($model) {
        if (!isset(self::getInstance()->_definition[$model])) {
            self::getInstance()->_definition[$model] = sfCouchdbJsonDefinitionParser::parse($model, 
                                                                                            self::getInstance()->getSchema(), 
                                                                                            new sfCouchdbJsonDefinition($model, ''));
        }

        return self::getInstance()->_definition[$model];
    }

    public static function getDefinitionByHash($model, $hash) {
        if (!isset(self::getInstance()->_definition_hash[$model][$hash])) {
            self::getInstance()->_definition_hash[$model][$hash] = self::getDefinition($model)->getDefinitionByHash($hash);
        } 

        return self::getInstance()->_definition_hash[$model][$hash];
    }

    public static function getDefinitionHashTree($model, $class_tree) {
        if (!isset(self::getInstance()->_definition_tree_hash[$class_tree])) {
            self::getInstance()->_definition_tree_hash[$class_tree] = self::getDefinition($model)->findHashByClassName($class_tree);
        }
        
        return self::getInstance()->_definition_tree_hash[$class_tree];
    }
}
