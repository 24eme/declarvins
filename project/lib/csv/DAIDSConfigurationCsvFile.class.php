<?php

class DAIDSConfigurationCsvFile extends CsvFile 
{
  const CSV_DAIDS_CONFIGURATION_INTERPRO = 0;
  const CSV_DAIDS_CONFIGURATION_STOCKS_MOYEN_VINIFIE = 1;
  const CSV_DAIDS_CONFIGURATION_STOCKS_MOYEN_NON_VINIFIE = 2;
  const CSV_DAIDS_CONFIGURATION_STOCKS_MOYEN_CONDITIONNE = 3;
  const CSV_DAIDS_CONFIGURATION_RESERVE_BLOQUE = 4;
  
  const DAIDS_CONFIGURATION_LIBELLE = 'libelle';
  const DAIDS_CONFIGURATION_TAUX = 'taux';
  
  private static $nodes = array(
    'vinifie',
  	'non_vinifie',
  	'conditionne'
  );
  
  protected $config;
  protected $errors;
  
  public function __construct($config, $file) 
  {
    parent::__construct($file);
    $this->config = $config;
  }
  


  public function importConfigurationDAIDS() 
  {
    $this->errors = array();
    $this->numline = 0;
    $csvs = $this->getCsv();
    $ligne = 0;
    foreach ($csvs as $line) {
      $ligne++;
      if (!$line[0])
		continue;
      $configurationDAIDS = $this->config->daids->interpro->getOrAdd('INTERPRO-'.$line[self::CSV_DAIDS_CONFIGURATION_INTERPRO]);
      try {
      	foreach ($this->getNodes() as $node) {
      		$configurationDAIDS = $this->setStocksMoyenDatas($configurationDAIDS, $node, $this->getArrayCsvValue($line[$this->getStocksMoyenConst($node)]));
      	}
		$configurationDAIDS = $this->setReserveBloqueDatas($configurationDAIDS, 'reserve_bloque', $this->getArrayCsvSimpleValue($line[self::CSV_DAIDS_CONFIGURATION_RESERVE_BLOQUE]));
      }catch(Exception $e) {
			$this->errors[] = array('ligne' => $ligne, 'message' => $e->getMessage());
      }
    }
    return $this->config;
  }
  
  private function setStocksMoyenDatas($config, $node, $datas) {
	foreach ($datas as $key => $val) {
		foreach ($datas[$key] as $k => $v) {
    		$detail = $config->stocks_moyen->{$node}->getOrAdd($key);
    		$detail->{$k} = $v;
		}
    }
    return $config;
  }

  
  private function setReserveBloqueDatas($config, $node, $datas) {
	foreach ($datas as $key => $val) {
    		$detail = $config->{$node};
    		$detail->{$key} = $val;
    }
    return $config;
  }
  private function getStocksMoyenConst($node) {
  	return constant('DAIDSConfigurationCsvFile::CSV_DAIDS_CONFIGURATION_STOCKS_MOYEN_'.strtoupper($node));
  }
  
  private function getArrayCsvValue($value)
  {
  	$tab = array();
  	$datas = explode('|', $value);
  	foreach ($datas as $data) {
  		if ($data) {
	  		$values = explode(':', $data);
	  		if (isset($values[0]) && isset($values[1])) {
	  			$sub_values = explode('&', $values[1]);
	  			if (isset($sub_values[0]) && isset($sub_values[1])) {
	  				$tab[$values[0]][self::DAIDS_CONFIGURATION_LIBELLE] = $sub_values[0];
	  				$tab[$values[0]][self::DAIDS_CONFIGURATION_TAUX] = $sub_values[1];
	  			}
	  		}
  		}
  	}
  	return $tab;
  }
  
  private function getArrayCsvSimpleValue($value)
  {
  	$tab = array();
  	$values = explode(':', $value);
  	if (isset($values[0]) && isset($values[1])) {
  		$tab[$values[0]] = $values[1];
  	}
  	return $tab;
  }
  
  public function getNodes()
  {
  	return self::$nodes;
  }

  public function getErrors() 
  {
    return $this->errors;
  }
}