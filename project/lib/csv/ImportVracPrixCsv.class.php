<?php

class ImportVracPrixCsv 
{
	
    const COL_VISA = 0;
    const COL_PRIX = 1;
    const COL_TYPE_PRIX = 2;

    protected $_csv = array();
    protected $_errors = array();
    protected $typesPrix = array();

    public function __construct($csv) {
        $this->_csv = $csv;
        foreach (ConfigurationClient::getCurrent()->vrac->interpro as $key => $value) {
        	if (preg_match('/INTERPRO/', $key)) {
        		$this->typesPrix = array_merge($this->typesPrix, $value->getTypesPrix()->toArray());
        	}
        }
    }
    
    public function getErrors() {
    	return $this->_errors;
    }
    
    protected function existLine($ligne, $line)
    {
    	$errors = array();
   		if (!isset($line[self::COL_VISA])) {
   			$errors[] = ('Colonne (indice '.(self::COL_VISA + 1).') "Numéro Visa" manquante');
   		}
    	if (!isset($line[self::COL_PRIX])) {
   			$errors[] = ('Colonne (indice '.(self::COL_PRIX + 1).') "Prix" manquante');
   		}
    	if (!isset($line[self::COL_TYPE_PRIX])) {
   			$errors[] = ('Colonne (indice '.(self::COL_TYPE_PRIX + 1).') "Type de prix" manquante');
   		}
   		if (count($errors) > 0) {
   			$this->_errors[$ligne] = $errors;
   			throw new sfException('has errors');
   		}
    }

	public function update() 
    {
    	$cpt = 0;
    	$ligne = 0;
      	foreach ($this->_csv as $line) {
      		$ligne++;
      		try {
      			$this->existLine($ligne, $line);
      		} catch (sfException $e) {
      			continue;
      		}
      		
      		$vrac = VracClient::getInstance()->find('VRAC-'.trim($line[self::COL_VISA]));
      		if (!$vrac) {
      			$this->_errors[$ligne] = array('Le contrat '.trim($line[self::COL_VISA]).' n\'existe pas.');
      			continue;
      		}
      		
      		$master = VracSoussigneIdentifiantView::getInstance()->findContratMaster($vrac->vendeur_identifiant, trim($line[self::COL_VISA]));
      		if (!$master) {
      			$this->_errors[$ligne] = array('Le contrat '.trim($line[self::COL_VISA]).' n\'est pas vise.');
      			continue;
      		}
      		
      		$typePrix = $this->getTypePrix(trim($line[self::COL_TYPE_PRIX]));
      		if (!$typePrix) {
      			$this->_errors[$ligne] = array('Type de prix '.trim($line[self::COL_TYPE_PRIX]).' non reconnu.');
      			continue;
      		}
      		
      		$contrat = clone $master;
      		
      		$numero = (int)$contrat->getRectificative() + (int)$contrat->getModificative();
      		$contrat->version = Vrac::buildVersion(null, $numero + 1);
      		$contrat->constructId();
      		$contrat->referente = 1;
      		$contrat->determination_prix = null;
      		$contrat->determination_prix_date = null;
      		$contrat->prix_unitaire = $this->castFloat(trim($line[self::COL_PRIX]));
      		$contrat->type_prix = $typePrix;
      		$contrat->update();
      		$contrat->valide->date_saisie = date('c');
      		$contrat->save(false);
      		
      		$master->referente = 0;
      		$master->valide->statut = VracClient::STATUS_CONTRAT_ANNULE;
      		$master->commentaires = "Mise à jour du prix définitif";
      		$master->save(false);
      		
      		$cpt++;
      		
      	}
      	if (count($this->_errors) > 0) {
      		throw new sfException("has errors");
      	}
      	return $cpt;
    }
    
    protected function castFloat($float) 
    {
    	return floatval(str_replace(',', '.', $float));
    }
    
    protected function getTypePrix($type) 
    {
    	foreach ($this->typesPrix as $key => $value) {
    		if (preg_match('/'.$key.'/i', $type)) {
    			return $key;
    		}
    	}
    	return null;
    }
}

