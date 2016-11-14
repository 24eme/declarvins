<?php
class DRMCielCompare
{
	protected $xmlIn;
	protected $xmlOut;
	
	public function __construct($xmlIn, $xmlOut)
	{
		$this->xmlIn = $xmlIn;
		$this->xmlOut = $xmlOut;
	}
	
	public function getDiff()
	{
		$arrIn = $this->identifyKey($this->flattenArray($this->xmlToArray($this->xmlIn)));
		$arrOut = $this->identifyKey($this->flattenArray($this->xmlToArray($this->xmlOut)));
		
		$diff = array();
		foreach ($arrIn as $key => $value) {
			if (!isset($arrOut[$key]) && $value) {
				$diff[$key] = $value;
			}
			if (isset($arrOut[$key]) && $arrOut[$key] != $value) {
				$diff[$key] = $value;
			}
		}
		return $diff;
	}
	
	public function hasDiff()
	{
		return (count($this->getDiff()) > 0)? true : false;
	}

	private function xmlToArray($xml)
	{
		return json_decode(json_encode((array)$xml), true);
	}
	
	private function flattenArray($array)
	{
		return acCouchdbToolsJson::json2FlatenArray($array, null, '_');
	}
	
	private function identifyKey($array)
	{
		$patternProduit = '/\/produit\/\{array\}\/([0-9]+)\/\{array\}\//i';
		$patternCrd = '/\/compte-crd\/\{array\}\/([0-9]+)\/\{array\}\//i';
		$patternCentilisation = '/\/centilisation\/\{array\}\/([0-9]+)\/\{array\}\//i';
		$newKeyProduit = '';
		$newKeyCrd = '';
		$newKeyCentilisation = '';
		$result = array();
		foreach ($array as $key => $value) {
			if (preg_match('/\/produit\//i', $key) || preg_match('/\/compte-crd\//i', $key) || preg_match('/\/centilisation\//i', $key)) {
				if (preg_match('/\/observations/i', $key)) {
					continue;
				}
				if (preg_match($patternProduit, $key) && (preg_match('/code-inao/i', $key) || preg_match('/libelle-fiscal/i', $key))) {
					$newKeyProduit = $value;
					continue;
				}
				if (preg_match($patternProduit, $key) && preg_match('/libelle-personnalise/i', $key)) {
					$newKeyProduit .= '_'.KeyInflector::slugify($value);
					continue;
				}
				if (preg_match($patternCrd, $key) && preg_match('/categorie-fiscale-capsules/i', $key)) {
					$newKeyCrd = $value;
					continue;
				}
				if (preg_match($patternCrd, $key) && preg_match('/type-capsule/i', $key)) {
					$newKeyCrd .= '_'.$value;
					continue;
				}
				if (preg_match($patternCentilisation, $key) && preg_match('/@attributes/i', $key)) {
					$newKeyCentilisation = $value;
					continue;
				}
				$value = $this->cleanValue($value);
				if (preg_match($patternProduit, $key)) {
					$tmp = preg_replace($patternProduit, '/produit/{array}/'.$newKeyProduit.'/{array}/', $key);
					$result[$tmp] = $value;
				} elseif (preg_match($patternCrd, $key) || preg_match($patternCentilisation, $key)) {
					$tmp = preg_replace($patternCrd, '/compte-crd/{array}/'.$newKeyCrd.'/{array}/', $key);
					$tmp = preg_replace($patternCentilisation, '/centilisation/{array}/'.$newKeyCentilisation.'/{array}/', $tmp);
					$result[$tmp] = $value;		
				} else {
					$result[$key] = $value;
				}
			}
		}
		return $result;
	}

	private function cleanValue($value)
	{
		if ($value == "false") {
			return 0;
		}
		if ($value == "true") {
			return 1;
		}
		if (is_numeric($value)) {
			return $value * 1;
		}
		return $value;
	}
}