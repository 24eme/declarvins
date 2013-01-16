<?php

class ImportVolumesBloquesCsv 
{
	
    const COL_ETABLISSEMENT_IDENTIFIANT = 0;
    const COL_ETABLISSEMENT_RAISONSOCIALE = 1;
    const COL_PRODUIT_CATEGORIE = 2;
    const COL_PRODUIT_GENRE = 3;
    const COL_PRODUIT_DENOMINATION = 4;
    const COL_PRODUIT_LIEU = 5;
    const COL_PRODUIT_COULEUR = 6;
    const COL_PRODUIT_CEPAGE = 7;
    const COL_PRODUIT_VOLUMEBLOQUE = 8;

    protected $_interpro = null;
    protected $_csv = array();

    public function __construct(Interpro $interpro) {
        $file_uri = $interpro->getAttachmentUri("volumes-bloques.csv");
        $this->_interpro = $interpro;
        $this->_csv = array();
    	if (@file_get_contents($file_uri)) {
	        $handler = fopen($file_uri, 'r');
	        if (!$handler) {
	            throw new Exception('Cannot open csv file anymore');
	        }
	        while (($line = fgetcsv($handler, 0, ";")) !== FALSE) {
	            if (preg_match('/[0-9]+/', trim($line[EtablissementCsv::COL_ID]))) {
	                $this->_csv[] = $line;
	            }
	        }
	        fclose($handler);
    	}
    }
    
	private function getProduitHashKey($line) 
  	{
  		try {
  			$hash = 'certifications_'.$this->getKey($line[self::COL_PRODUIT_CATEGORIE]).
                '_genres_'.$this->getKey($line[self::COL_PRODUIT_GENRE], true).
                '_appellations_'.$this->getKey($line[self::COL_PRODUIT_DENOMINATION], true).
                '_mentions_'.Configuration::DEFAULT_KEY.
                '_lieux_'.$this->getKey($line[self::COL_PRODUIT_LIEU], true).
                '_couleurs_'.strtolower($this->couleurKeyToCode($line[self::COL_PRODUIT_COULEUR])).
                '_cepages_'.$this->getKey($line[self::COL_PRODUIT_CEPAGE], true);
  	
    		return $hash;
  		} catch (Exception $e) {
  			print_r($line);
  			echo "\n";
 		}
  	}
  
  	private function couleurKeyToCode($key) 
  	{
    	$correspondances = array(1 => "rouge",
                                 2 => "rose",
                                 3 => "blanc");

    	return $correspondances[$key];
  	}
  
  	private function getKey($key, $withDefault = false) 
  	{
		if ($withDefault) {
  			return ($key)? $key : Configuration::DEFAULT_KEY;
  		} elseif (!$key) {
  			throw new Exception('La clé "'.$key.'" n\'est pas valide');
  		} else {
  			return $key;
  		}
  	}

	public function updateOrCreate() 
    {
    	$cpt = 0;
    	$etabTmp = null;
    	$etab = null;
      	foreach ($this->_csv as $line) {
      		if (!$etabTmp || trim($line[self::COL_ETABLISSEMENT_IDENTIFIANT]) != $etab->identifiant) {
	  			if ($etab) {
	  				$etab->save();
	  			}
      			$etabTmp = $etab = EtablissementClient::getInstance()->find(trim($line[self::COL_ETABLISSEMENT_IDENTIFIANT]));
	  			$cpt++;
      		}
			if ($etab) {
				$p = $etab->produits->add($this->getProduitHashKey($line));
				if ($line[self::COL_PRODUIT_VOLUMEBLOQUE]) {
					$p->volume_bloque = round(floatval($line[self::COL_PRODUIT_VOLUMEBLOQUE]), 2);
				} else {
					$p->volume_bloque = null;
				}
			}
      	}
  		if ($etab) {
  			$etab->save();
  		}
      	return $cpt;
    }
}

