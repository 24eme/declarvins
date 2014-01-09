<?php

class ConfigurationProduitClient extends acCouchdbClient 
{
	const PREFIXE_ID = 'CONFIGURATION-PRODUITS';
	
	protected static $format_indexes = array(
		'%c%' => 0,
        '%g%' => 1,
        '%a%' => 2,
        '%m%' => 3,
        '%l%' => 4,
        '%co%' => 5,
        '%ce%' => 6
	);
	
	public static function getInstance() 
	{
	  	return acCouchdbManager::getClient("ConfigurationProduit");
	}
	
	public function buildId($interpro)
	{
		return self::PREFIXE_ID.'-'.$this->getIdentifiantInterpro($interpro);
	}
	
	public function getOrCreate($interpro)
	{
		$configurationProduits = $this->getByInterpro($interpro);
		if (!$configurationProduits) {
			$configurationProduits = new ConfigurationProduit();
	  		$configurationProduits->interpro = $interpro;
		}
		return $configurationProduits;
	}
	
	public function getByInterpro($interpro)
	{
		return $this->find($this->buildId($interpro));
	}
	
	private function getIdentifiantInterpro($interpro)
	{
		return str_replace('INTERPRO-', '', $interpro);
	}
	
    public function format($items, $labels = array(), $format = "%g% %a% %m% %l% %co% %ce%") 
    {
        $format_index = self::getFormatIndexes();
        $result = $format;
        foreach($format_index as $key => $item) {
          	if (isset($items[$item])) {
            	$result = str_replace($key, $items[$item], $result);
          	} else {
            	$result = str_replace($key, "", $result);
          	}
        }
        $result = preg_replace('/ +/', ' ', $result);
        if (count($labels) > 0) { 
        	return $this->formatLabels($labels, $result);
        } else {
        	$result = str_replace("%la%", '', $result);
        }
        return $result;
    }
    
    protected static function getFormatIndexes()
    {
    	return self::$format_indexes;
    }

    public function formatLabels($labels, $string, $separator = ", ") 
    {
    	if (!is_array($labels)) {
    		$labels = array($labels);
    	}
        return str_replace("%la%", implode($separator, $labels), $string);
    }
}
