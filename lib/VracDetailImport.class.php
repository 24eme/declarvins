<?php

class VracDetailImport
{
  
	protected $logs;
	protected $datas;
	protected $client;
	protected $config;
	protected $loggeur;
  
	public function __construct(array $datas, VracClient $client, ConfigurationVrac $config) 
	{
		$this->logs = array();
		$this->datas = $datas;
		$this->client = $client;
		$this->config = $config;
		$this->loggeur = new VracDetailLoggeur();
	}
	
	public function getVrac() 
  	{    	
    	try {
  			$vrac = $this->parseVrac();
    		$this->parseLot($vrac);
    		$date = ($vrac->valide->date_saisie)? date('Y-m-d', strtotime($vrac->valide->date_saisie)) : null;
    		$result = ConfigurationClient::getCurrent()->getConfigurationProduit($vrac->produit)->getCurrentDroit(DRMDroits::DROIT_CVO, $date, true);
	        if ($result) {
		        $vrac->part_cvo = $result->taux;
	        }
	        $vrac->has_cotisation_cvo = 1;
	        if ($vrac->interpro == 'INTERPRO-CIVP') {
	        	$vrac->has_cotisation_cvo = 0;
	        }
			$vrac->storeSoussignesInformations();
			$vrac->update();
    	} catch (Exception $e) {
			$this->loggeur->addLog($e->getMessage());    	
    	}
  		return $vrac;
  	}

	private function parseVrac() 
  	{
  		$numContrat = $this->getDataValue(VracDateView::VALUE_VRAC_ID, 'numéro visa', true);
  		$vrac = VracClient::getInstance()->find('VRAC-'.$numContrat);
  		if (!$vrac) {
  			$vrac = new Vrac();
  		}
  		$vrac->mode_de_saisie = Vrac::MODE_DE_SAISIE_PAPIER;
  		$vrac->interpro = $this->config->getInterproId();
    	$vrac->numero_contrat = $this->getDataValue(VracDateView::VALUE_VRAC_ID, 'numéro visa', true);
    	$vrac->valide->date_saisie = $this->datize($this->getDataValue(VracDateView::VALUE_DATE_SAISIE, 'vrac date de saisie'), VracDateView::VALUE_DATE_SAISIE, 'vrac date de saisie');
    	$vrac->valide->date_validation = $this->datize($this->getDataValue(VracDateView::VALUE_DATE_VALIDATION, 'vrac date de validation'), VracDateView::VALUE_DATE_VALIDATION, 'vrac date de validation');
    	$vrac->mandataire_exist = 0;
    	$vrac->acheteur_identifiant = $this->getDataValue(VracDateView::VALUE_ACHETEUR_ID, 'identifiant acheteur', false);
    	$vrac->vendeur_identifiant = $this->getDataValue(VracDateView::VALUE_VENDEUR_ID, 'identifiant vendeur', false);
    	$vrac->mandataire_identifiant = $this->getDataValue(VracDateView::VALUE_MANDATAIRE_ID, 'identifiant courtier', false);
    	$vrac->valide->date_validation_vendeur = $vrac->valide->date_validation;
    	$vrac->valide->date_validation_acheteur = $vrac->valide->date_validation;
    	$vrac->valide->date_validation_mandataire = null;
    	if ($vrac->mandataire_identifiant) {
    		$vrac->mandataire_exist = 1;
    		$vrac->valide->date_validation_mandataire = $vrac->valide->date_validation;
    	}  	
    	if ($this->datas[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]) {
    		if ($r = $this->config->getKeyAndLibelle('types_transaction', $this->datas[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE])) {
    			foreach ($r as $data) {
    				$vrac->type_transaction_libelle = $data['libelle'];
    				$vrac->type_transaction = $data['key'];
    			}
    		}
    	}
    	$vrac->produit = $this->getHashProduit();
    	$vrac->millesime = $this->getDataValue(VracDateView::VALUE_MILLESIME, 'millesime', false, "/^[0-9]{4}$/");
    	if (!$vrac->millesime) {
    		$vrac->millesime = $this->getDataValue(VracDateView::VALUE_MILLESIME_CODE, 'millesime', false, "/^[0-9]{4}$/");	
    	}
    	if ($this->datas[VracDateView::VALUE_LABELS_LIBELLE]) {
    		if ($r = $this->config->getKeyAndLibelle('labels', $this->datas[VracDateView::VALUE_LABELS_LIBELLE])) {
    			foreach ($r as $data) {
    				$vrac->labels_libelle = $data['libelle'];
    				$vrac->labels = $data['key'];
    			}
    		}
    	}
  		if ($this->datas[VracDateView::VALUE_MENTIONS]) {
  			$mentions = explode('|', $this->datas[VracDateView::VALUE_MENTIONS]);
  			foreach ($mentions as $mention) {
	    		if ($r = $this->config->getKeyAndLibelle('mentions', $mention)) {
	    			$libelles = array();
	    			foreach ($r as $data) {
	    				$vrac->mentions->getOrAdd($data['key']);
	    				$libelles[] = $data['libelle'];
	    			}
	    			$vrac->mentions_libelle = implode(', ', $libelles);
	    		}
  			}
    	}
    	if ($this->datas[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]) {
    		if ($r = $this->config->getKeyAndLibelle('cas_particulier', $this->datas[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE])) {
    			foreach ($r as $data) {
    				$vrac->cas_particulier_libelle = $data['libelle'];
    				$vrac->cas_particulier = $data['key'];
    			}
    		}
    	}
    	if ($this->datas[VracDateView::VALUE_TYPE_PRIX]) {
    		if ($r = $this->config->getKeyAndLibelle('types_prix', $this->datas[VracDateView::VALUE_TYPE_PRIX])) {
    			foreach ($r as $data) {
    				$vrac->type_prix = $data['key'];
    			}
    		}
    	}
    	if ($this->datas[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]) {
    		if ($r = $this->config->getKeyAndLibelle('conditions_paiement', $this->datas[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE])) {
    			foreach ($r as $data) {
    				$vrac->conditions_paiement_libelle = $data['libelle'];
    				$vrac->conditions_paiement = $data['key'];
    			}
    		}
    	}
    	$vrac->vin_livre = ($this->datas[VracDateView::VALUE_VIN_LIVRE])? strtolower(KeyInflector::unaccent(trim($this->datas[VracDateView::VALUE_VIN_LIVRE]))) : null;
    	$vrac->premiere_mise_en_marche = ($this->datas[VracDateView::VALUE_PREMIERE_MISE_EN_MARCHE] == 1)? 1 : 0;
    	$vrac->annexe = ($this->datas[VracDateView::VALUE_ANNEXE] == 1)? 1 : 0;
    	$vrac->volume_propose = ($this->datas[VracDateView::VALUE_VOLUME_PROPOSE])? sprintf('%2f', $this->floatize($this->datas[VracDateView::VALUE_VOLUME_PROPOSE])) : null;
    	$vrac->prix_unitaire = ($this->datas[VracDateView::VALUE_PRIX_UNITAIRE])? sprintf('%2f', $this->floatize($this->datas[VracDateView::VALUE_PRIX_UNITAIRE])) : null;
    	$vrac->determination_prix = $this->getDataValue(VracDateView::VALUE_DETERMINATION_PRIX, 'mode de détermination du prix', false);
    	$vrac->export = ($this->datas[VracDateView::VALUE_EXPORT] == 1)? 1 : 0;
    	$vrac->reference_contrat_pluriannuel = $this->getDataValue(VracDateView::VALUE_REFERENCE_CONTRAT_PLURIANNUEL, 'reference contrat pluriannuel', false);
    	$vrac->date_debut_retiraison = $this->datizeMin($this->datize($this->getDataValue(VracDateView::VALUE_DATE_DEBUT_RETIRAISON, 'vrac date début de retiraison', false), VracDateView::VALUE_DATE_DEBUT_RETIRAISON, 'vrac date début de retiraison'));
    	$vrac->date_limite_retiraison = $this->datizeMin($this->datize($this->getDataValue(VracDateView::VALUE_DATE_LIMITE_RETIRAISON, 'vrac date limite de retiraison', false), VracDateView::VALUE_DATE_LIMITE_RETIRAISON, 'vrac date limite de retiraison'));
  	
    	if ($echeancier = $this->datas[VracDateView::VALUE_PAIEMENTS_DATE]) {
    		$dates = explode('|', $echeancier);
    		$montants = explode('|', $this->datas[VracDateView::VALUE_PAIEMENTS_MONTANT]);
    		$volumes = explode('|', $this->datas[VracDateView::VALUE_PAIEMENTS_VOLUME]);
    		foreach ($dates as $k => $date) {
    			$e = $vrac->paiements->add();
    			$e->date = $this->datizeMin($this->datize($date, VracDateView::VALUE_PAIEMENTS_DATE, 'échéancier date'));
    			$e->montant = (isset($montants[$k]))? sprintf('%2f', $this->floatize($montants[$k])) : null;
    			$e->volume = (isset($volumes[$k]))? sprintf('%2f', $this->floatize($volumes[$k])) : null;
    		}
    	}
    	$vrac->valide->statut = $this->getDataValue(VracDateView::VALUE_STATUT, 'statut', false);
    	return $vrac;
  	}
  	
  	private function floatize($value)
  	{
  		return floatval(str_replace(',', '.', $value));
  	}

	private function parseLot($vrac) 
  	{
  		$numeroLot = $this->getDataValue(VracDateView::VALUE_LOT_NUMERO, 'numero de lot', false);
  		if ($numeroLot) {
  			$lot = $vrac->lots->add();
  			$lot->numero = $numeroLot;
  			$lot->assemblage = ($this->datas[VracDateView::VALUE_LOT_ASSEMBLAGE] == 1)? 1 : 0;
  			$lot->degre = $this->getDataValue(VracDateView::VALUE_LOT_DEGRE, 'lot degré', false);
  			$lot->presence_allergenes = ($this->datas[VracDateView::VALUE_LOT_PRESENCE_ALLERGENES] == 1)? 1 : 0;
  			/*$lot->bailleur = $this->getDataValue(VracDateView::VALUE_LOT_BAILLEUR, 'lot bailleur', false);
  			if ($lot->bailleur) {
  				$lot->metayage = 1;
  			}*/
	  		if ($cuves = $this->datas[VracDateView::VALUE_LOT_CUVES_NUMERO]) {
	    		$numeros = explode('|', $cuves);
	    		$dates = explode('|', $this->datas[VracDateView::VALUE_LOT_CUVES_DATE]);
	    		$volumes = explode('|', $this->datas[VracDateView::VALUE_LOT_CUVES_VOLUME]);
	    		foreach ($numeros as $k => $numero) {
	    			$c = $lot->cuves->add();
	    			$c->numero = $this->getDataValue($numero, VracDateView::VALUE_LOT_CUVES_NUMERO, 'lot cuve numéro', true);
	    			$c->date = (isset($dates[$k]))? $this->datizeMin($this->datize($dates[$k], VracDateView::VALUE_LOT_CUVES_DATE, 'lot cuve date')) : null;
	    			$c->volume = (isset($volumes[$k]))? sprintf('%2f', $this->floatize($volumes[$k])) : null;
	    		}
	    	}
	  		if ($millesimes = $this->datas[VracDateView::VALUE_LOT_MILLESIMES_ANNEE]) {
	    		$annees = explode('|', $millesimes);
	    		$pourcentages = explode('|', $this->datas[VracDateView::VALUE_LOT_MILLESIMES_POURCENTAGE]);
	    		foreach ($annees as $k => $annee) {
	    			$m = $lot->millesimes->add();
	    			$m->annee = $this->getDataValue($annee, VracDateView::VALUE_LOT_MILLESIMES_ANNEE, 'lot année millésime', false, '/^[0-9]{4}$/');
	    			$m->pourcentage = (isset($pourcentages[$k]))? sprintf('%2f', $this->floatize($pourcentages[$k])) : null;
	    		}
	    	}
  		}
  	}
  
  	private function getDataValue($dataIndice, $dataName, $required = false, $regexp = null)
  	{
  		if ($this->datas[$dataIndice] == " ") {
  			$this->datas[$dataIndice] = null;
  		}
  		if ($required && !$this->datas[$dataIndice]) {
  			$this->loggeur->addEmptyColumnLog($dataIndice, $dataName);
  			return null;
  		}
  		if (!empty($this->datas[$dataIndice]) && $regexp && !preg_match($regexp, $this->datas[$dataIndice])) {
  			if ($required) {
  				$this->loggeur->addInvalidColumnLog($dataIndice, $dataName, $this->datas[$dataIndice]);
  			}
  			return null;
  		}
  		return ($this->datas[$dataIndice])? $this->datas[$dataIndice] : null;
  	}  
  
	private function getHashProduit() 
	{
		$hash = 'declaration/certifications/'.$this->getKey($this->datas[VracDateView::VALUE_PRODUIT_CERTIFICATION_CODE]).
                '/genres/'.$this->getKey($this->datas[VracDateView::VALUE_PRODUIT_GENRE_CODE], true).
                '/appellations/'.$this->getKey($this->datas[VracDateView::VALUE_PRODUIT_APPELLATION_CODE], true).
                '/mentions/'.ConfigurationProduit::DEFAULT_KEY.
                '/lieux/'.$this->getKey($this->datas[VracDateView::VALUE_PRODUIT_LIEU_CODE], true).
                '/couleurs/'.strtolower($this->couleurKeyToCode($this->datas[VracDateView::VALUE_PRODUIT_COULEUR_CODE])).
                '/cepages/'.$this->getKey($this->datas[VracDateView::VALUE_PRODUIT_CEPAGE_CODE], true);
		return $hash;
	}
  
	private function getKey($key, $withDefault = false) 
	{
		if ($key == " ") {
  			$key = null;
  		}
		if ($withDefault) {
			return ($key)? $key : ConfigurationProduit::DEFAULT_KEY;
		} elseif (!$key) {
			throw new Exception('La clé "'.$key.'" n\'est pas valide');
		} else {
			return $key;
		}
	}
  
	private function couleurKeyToCode($key) 
	{
		$correspondances = array(1 => "rouge",
                                 2 => "rose",
                                 3 => "blanc");
		if (!in_array($key, array_keys($correspondances))) {
			return $key;
		}
		return $correspondances[$key];
	}
	
	public function datize($str, $dataIndice, $dataName) 
	{
  		if (!$str) {
  			return null;
  		}
    	if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $str)) {
      		return $str;
    	}
    	if (preg_match('/^\d{4}-\d{2}-\d{2}([^T]|$)/', $str)) {
      		return $str.'T00:00:00Z';
    	}
    	if (preg_match('/^T[\.]*/', $str)) {
      		return null;
    	}
    	if (preg_match('/\//', $str)) {
      		$str = preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', '\3-\2-\1', $str);
      		return $str.'T00:00:00Z' ;
    	}
    	if (preg_match('/2012$/', $str)) {
      		$str = preg_replace('/(\d{2})(\d{2})2012/', '2012-\2-\1', $str);
      		return $str.'T00:00:00Z' ;
    	}
    	$this->loggeur->addInvalidColumnLog($dataIndice, $dataName, $this->datas[$dataIndice]);
  	}
  	
  	public function datizeMin($str)
  	{
  		if ($str) {
  			return date("Y-m-d", strtotime($str));
  		}
  		return null;
  	}
  	
  	public function hasErrors()
  	{
  		return $this->loggeur->hasLogs();
  	}

	public function getLogs() 
	{
    	return $this->loggeur->getLogs();
	}
}
