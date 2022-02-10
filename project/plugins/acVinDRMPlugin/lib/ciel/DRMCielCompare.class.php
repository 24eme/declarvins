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
		$arrIn = $this->updateReplacementKeys($this->identifyKey($this->flattenArray($this->xmlToArray($this->xmlIn)))); // CIEL
		$arrOut = $this->updateReplacementKeys($this->identifyKey($this->flattenArray($this->xmlToArray($this->xmlOut)))); // INTERPRO

		$diff = array();
		$stocksIn = 0;
		$stocksOut = 0;
		foreach ($arrIn as $key => $value) {
			if (!isset($arrOut[$key]) && $value) {
				$diff[$key] = $value;
			}
			if (isset($arrOut[$key]) && $arrOut[$key] != $value) {
				$diff[$key] = $value;
			}
			if (preg_match('/stock-debut-periode/', $key) && !preg_match('/compte-crd/', $key)) {
				$stocksIn += $value;
			}
			if (preg_match('/stock-fin-periode/', $key) && !preg_match('/compte-crd/', $key)) {
				$stocksIn += $value;
			}
		}
		foreach ($arrOut as $key => $value) {
			if (!isset($arrIn[$key]) && $value) {
				$diff[$key] = $value;
			}
			if (preg_match('/stock-debut-periode/', $key) && !preg_match('/compte-crd/', $key)) {
				$stocksOut += $value;
			}
			if (preg_match('/stock-fin-periode/', $key) && !preg_match('/compte-crd/', $key)) {
				$stocksOut += $value;
			}
		}
		if (!$stocksOut && !$stocksIn) {
			$diff = array();
		}
		return $diff;
	}

	public function getLitteralDiff()
	{
		$arrIn = $this->updateReplacementKeys($this->identifyKey($this->flattenArray($this->xmlToArray($this->xmlIn)))); // CIEL
		$arrOut = $this->updateReplacementKeys($this->identifyKey($this->flattenArray($this->xmlToArray($this->xmlOut)))); // INTERPRO

		$diff = array();
		$stocksIn = 0;
		$stocksOut = 0;
		foreach ($arrIn as $key => $value) {
			if (!isset($arrOut[$key]) && $value) {
				$diff['Donnée ajoutée / '.$this->cleanKey($key)] = "$value (CIEL)";
			}
			if (isset($arrOut[$key]) && $arrOut[$key] != $value) {
				$diff['Donnée modifiée / '.$this->cleanKey($key)] = "$value (CIEL) / $arrOut[$key] (Interpro.)";
			}
			if (preg_match('/stock-debut-periode/', $key)) {
				$stocksIn += $value;
			}
			if (preg_match('/stock-fin-periode/', $key)) {
				$stocksIn += $value;
			}
		}
		foreach ($arrOut as $key => $value) {
			if (!isset($arrIn[$key]) && $value) {
				$diff['Donnée supprimée / '.$this->cleanKey($key)] = "$value (Interpro.)";
			}
			if (preg_match('/stock-debut-periode/', $key)) {
				$stocksOut += $value;
			}
			if (preg_match('/stock-fin-periode/', $key)) {
				$stocksOut += $value;
			}
		}
		if (!$stocksOut && !$stocksIn) {
			$diff = array();
		}
		return $diff;
	}

	private function updateReplacementKeys($arr)
	{
	    $updatedArr = array();
	    foreach ($arr as $key => $value) {
	        if (preg_match('/replacement-suspension\/{array}\/[0-9]+\/{array}/', $key)) {
	            $explodedKey = explode('/', $key);
	            if (count($explodedKey) > 0) {
	                $explodedKey[count($explodedKey)-1] = 'mois';
	                $moisKey = implode('/', $explodedKey);
	                $explodedKey[count($explodedKey)-1] = 'annee';
	                $anneeKey = implode('/', $explodedKey);
	                if (isset($arr[$moisKey]) && isset($arr[$anneeKey])) {
	                    $explodedKey = explode('/', $key);
	                    $explodedKey[count($explodedKey)-3] = $arr[$anneeKey].$arr[$moisKey];
	                    $updatedArr[implode('/', $explodedKey)] = $value;
	                }
	            }
	        } else {
	            $updatedArr[$key] = $value;
	        }
	    }
	    return $updatedArr;
	}

	private function cleanKey($key)
	{
		$cleans = array(
			'_declaration-recapitulative_{array}/droits-acquittes/{array}/produit/{array}/' => 'DRM / droits acquittés / ',
			'_declaration-recapitulative_{array}/droits-suspendus/{array}/produit/{array}/' => 'DRM / droits suspendus / ',
			'_declaration-recapitulative_{array}/compte-crd/{array}/' => 'Compte CRD / ',
			'/{array}/' => ' / ',
			'/ @attributes / volume' => '',
		);
		foreach ($cleans as $k => $r) {
			$key = str_replace($k, $r, $key);
		}
		return $key;
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
					$newKeyProduit .= '_'.KeyInflector::slugify($value, false);
					continue;
				}
				if (!preg_match($patternCrd, $key) && preg_match('/\/compte-crd\/\{array\}\//i', $key)) {
					$key = str_replace('compte-crd/{array}', 'compte-crd/{array}/0/{array}', $key);
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
					if (preg_match('/volumePersonnalise/i', $key)) {
						$newKeyCentilisation .= sprintf("%01.02f", $value);
						continue;
					} elseif (preg_match('/bib/i', $key)) {
						$newKeyCentilisation .= ($value == 1 || $value == 'true')? 1 : 0;
						continue;
					} else {
						$newKeyCentilisation = $value;
						continue;
					}
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
