<?php

class VracConfigurationCsvFile extends CsvFile 
{
  const CSV_VRAC_CONFIGURATION_INTERPRO = 0;
  const CSV_VRAC_CONFIGURATION_VENDEUR_TYPES = 1;
  const CSV_VRAC_CONFIGURATION_ACHETEUR_TYPES = 2;
  const CSV_VRAC_CONFIGURATION_TYPES_TRANSACTION = 3;
  const CSV_VRAC_CONFIGURATION_LABELS = 4; 
  const CSV_VRAC_CONFIGURATION_MENTIONS = 5;
  const CSV_VRAC_CONFIGURATION_TYPES_PRIX = 6;
  const CSV_VRAC_CONFIGURATION_CONDITIONS_PAIEMENT = 7;
  const CSV_VRAC_CONFIGURATION_TYPES_CONTRAT = 8;
  const CSV_VRAC_CONFIGURATION_CVO_NATURES = 9;
  const CSV_VRAC_CONFIGURATION_CVO_REPARTITIONS = 10;
  const CSV_VRAC_CONFIGURATION_NATURES_DOCUMENT = 11;
  const CSV_VRAC_CONFIGURATION_TYPES_DOMAINE = 12;
  const CSV_VRAC_CONFIGURATION_DELAIS_PAIEMENT = 13;
  const CSV_VRAC_CONFIGURATION_CONTENANCES = 14;
  const CSV_VRAC_CONFIGURATION_ETAPES = 15;
  const CSV_VRAC_CONFIGURATION_COMMENTAIRES_LOT = 16;
  const CSV_VRAC_CONFIGURATION_CAS_PARTICULIER = 17;
  const CSV_VRAC_CONFIGURATION_CLAUSES = 18;
  const CSV_VRAC_CONFIGURATION_INFORMATIONS_COMPLEMENTAIRES = 19;
  
  private static $nodes = array(
    'etapes',
  	'vendeur_types',
  	'acheteur_types',
  	'types_transaction',
  	'labels',
  	'mentions',
  	'types_prix',
  	'conditions_paiement',
  	'types_contrat',
  	'cvo_natures',
  	'cvo_repartitions',
  	'natures_document',
  	'types_domaine',
  	'delais_paiement',
  	'contenances',
  	'commentaires_lot',
  	'cas_particulier',
    'clauses',
    'informations_complementaires'
  );
  
  private static $simpleDatas = array(
    'clauses',
    'informations_complementaires'
  );
  
  protected $config;
  protected $errors;
  
  public function __construct($config, $file) {
    parent::__construct($file);
    $this->config = $config;
  }
  


  public function importConfigurationVrac() {
    $this->errors = array();
    $this->numline = 0;
    $contrats = array();
    $csvs = $this->getCsv();
    $ligne = 0;
    foreach ($csvs as $line) {
      $ligne++;
      if (!$line[0])
		continue;
      $configurationVrac = $this->config->vrac->interpro->getOrAdd('INTERPRO-'.$line[self::CSV_VRAC_CONFIGURATION_INTERPRO]);
      try {
      	foreach ($this->getNodes() as $node) {
      		if (in_array($node, $this->getSimpleDatas())) {
      			$configurationVrac = $this->setDatas($configurationVrac, $node, $line[$this->getConst($node)]);
      		} else {
      			$configurationVrac = $this->setTabDatas($configurationVrac, $node, $this->getArrayCsvValue($line[$this->getConst($node)]));
      		}
      	}
		
      }catch(Exception $e) {
			$this->errors[] = array('ligne' => $ligne, 'message' => $e->getMessage());
      }
    }
    return $this->config;
  }
  
  private function setDatas($config, $node, $datas) {
    $config->{$node} = $datas;
    return $config;
  }
  
  private function setTabDatas($config, $node, $datas) {
	foreach ($datas as $key => $val) {
    	$config->{$node}->add($key, $val);
    }
    return $config;
  }
  
  private function getConst($node) {
  	return constant('VracConfigurationCsvFile::CSV_VRAC_CONFIGURATION_'.strtoupper($node));
  }
  
  private function getArrayCsvValue($value)
  {
  	$tab = array();
  	$datas = explode('|', $value);
  	foreach ($datas as $data) {
  		if ($data) {
	  		$values = explode(':', $data);
	  		if (isset($values[0]) && isset($values[1])) {
	  			$tab[$values[0]] = $values[1];
	  		} elseif (isset($values[0]) && !isset($values[1])) {
	  			$tab[] = $values[0];
	  		}
  		}
  	}
  	return $tab;
  }
  
  public function getNodes()
  {
  	return self::$nodes;
  }
  
  public function getSimpleDatas()
  {
  	return self::$simpleDatas;
  }

  public function getErrors() {
    return $this->errors;
  }
}