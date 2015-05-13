<?php

class DRMDetailImport
{
  
	protected $logs;
	protected $datas;
	protected $client;
	protected $loggeur;
  
	public function __construct(array $datas, DRMClient $client) 
	{
		$this->logs = array();
		$this->datas = $datas;
		$this->client = $client;
		$this->loggeur = new DRMDetailLoggeur();
	}
	
	public function getDrm() 
  	{
    	$drm = $this->parseDrm();
    	$hasDetail = true;
    	try {
    		$configuration = ConfigurationClient::getCurrent();
    		$hash = $this->getHashProduit();
    		if ($configuration->getConfigurationProduit($hash)) {
	  			$detail = $drm->addProduit($this->getHashProduit(), explode('|', $this->getDataValue(DRMDateView::VALUE_LABELS, 'drm detail labels')));
		      	switch($this->getDataValue(DRMDateView::VALUE_TYPE, 'type ligne', true, '/^(DETAIL|CONTRAT)$/')) {
					case 'DETAIL':
			  			$this->parseDetail($detail);
			  			break;
					case 'CONTRAT':
			  			$this->parseContrat($detail);
			  			break;
					default:
						break;
				}
    		} else {
				$this->loggeur->addLog('Pas de configuration pour le produit '.$hash);    	
				$hasDetail = false;	
    		}
    	} catch (Exception $e) {
			$this->loggeur->addLog($e->getMessage());    	
			$hasDetail = false;	
    	}
		try {
			$drm->validate();
		} catch (Exception $e) {
			$this->loggeur->addLog($e->getMessage());
		}
		if ($hasDetail) {
			$this->checkDetailTotaux($drm->getProduit($this->getHashProduit(), explode('|', $this->getDataValue(DRMDateView::VALUE_LABELS, 'drm detail labels'))));
		}
	  	$drm->valide->date_signee = $this->datize($this->getDataValue(DRMDateView::VALUE_DATEDESIGNATURE, 'drm date de signature'), DRMDateView::VALUE_DATEDESIGNATURE, 'drm date de signature');
	  	$drm->valide->date_saisie = $this->datize($this->getDataValue(DRMDateView::VALUE_DATEDESAISIE, 'drm date de saisie'), DRMDateView::VALUE_DATEDESAISIE, 'drm date de saisie');
  		return $drm;
  	}

	private function parseDrm() 
  	{
  		$drmDeclarant = $this->getDataValue(DRMDateView::VALUE_IDENTIFIANT_DECLARANT, 'drm identifiant declarant', true);
  		$drmAnnee = $this->getDataValue(DRMDateView::VALUE_ANNEE, 'drm année', true, '/^[0-9]{4}$/');
  		$drmMois = $this->getDataValue(DRMDateView::VALUE_MOIS, 'drm mois', true, '/^[0-9]{1,2}$/');
  		$drmMois = ($drmMois)? sprintf("%02d", $drmMois) : null;
  		$drmVersion = $this->getDataValue(DRMDateView::VALUE_VERSION, 'drm version', false, '/^[RM]{1}[0-9]{2}$/');
  		$drmPeriode = $this->client->buildPeriode($drmAnnee, $drmMois);
  		$drmId = $this->client->buildId($drmDeclarant, $drmPeriode, $drmVersion);
  		$drm = $this->client->find($drmId);
  		
  		if (!$drm) {
      		$drm = $this->client->createBlankDoc($drmDeclarant, $drmPeriode);
      		if ($drmDeclarant && ($etablissement = EtablissementClient::getInstance()->retrieveById($drmDeclarant))) {
      			$drm->setEtablissementInformations($etablissement);
      		}
      		$drm->version = $drmVersion;
      		$drm->mode_de_saisie = $this->getDataValue(DRMDateView::VALUE_MODEDESAISIE, 'drm mode de saisie');
      		$drm->identifiant_ivse = $this->getDataValue(DRMDateView::VALUE_IDIVSE, 'drm ivse id');
      		$drm->identifiant_drm_historique = $this->getDataValue(DRMDateView::VALUE_IDDRM, 'drm historique id');
      		
      		$drmPrecedenteAnnee = $this->getDataValue(DRMDateView::VALUE_ANNEE_PRECEDENTE, 'drm année précédente', false, '/^[0-9]{4}$/');
  			$drmPrecedenteMois = $this->getDataValue(DRMDateView::VALUE_MOIS, 'drm mois précédente', false, '/^[0-9]{1,2}$/');
  			$drmPrecedenteMois = ($drmPrecedenteMois)? sprintf("%02d", $drmPrecedenteMois) : null;
  			$drmPrecedenteVersion = $this->getDataValue(DRMDateView::VALUE_VERSION_PRECEDENTE, 'drm version précédente', false, '/^[RM]{1}[0-9]{2}$/');
      		$drm->precedente = ($drmPrecedenteAnnee && $drmPrecedenteMois)? $this->client->buildId($drmDeclarant, $this->client->buildPeriode($drmPrecedenteAnnee, $drmPrecedenteMois), $drmPrecedenteVersion) : null;
      	}
      	
      	return $drm;
  	}
  	
	private function parseContrat($detail) 
  	{
  		$numContrat = $this->getDataValue(DRMDateView::VALUE_CONTRAT_NUMERO, 'drm contrat numéro');
  		$volContrat = $this->getDataValue(DRMDateView::VALUE_CONTRAT_VOLUME, 'drm contrat volume');
  		if ($numContrat && $volContrat) {
    		$detail->addVrac($numContrat, $volContrat);
  		}
  	}

	private function parseDetail($detail) 
  	{
    	$detail->total_debut_mois = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS]);
      	$detail->stocks_debut->bloque = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE]);
      	$detail->stocks_debut->warrante = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE]);
      	$detail->stocks_debut->instance = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE]);
      	$detail->stocks_debut->commercialisable = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE]);
      	$detail->entrees->achat = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES_ACHAT]);
      	$detail->entrees->recolte = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE]);
      	$detail->entrees->repli = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES_REPLI]);
      	$detail->entrees->declassement = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT]);
      	$detail->entrees->mouvement = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT]);
      	$detail->entrees->crd = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES_CRD]);
      	$detail->sorties->vrac = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_VRAC]);
      	$detail->sorties->export = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_EXPORT]);
      	$detail->sorties->factures = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_FACTURES]);
      	$detail->sorties->crd = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_CRD]);
      	$detail->sorties->consommation = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION]);
      	$detail->sorties->pertes = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_PERTES]);
      	$detail->sorties->declassement = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT]);
      	$detail->sorties->repli = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_REPLI]);
      	$detail->sorties->mouvement = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT]);
      	$detail->sorties->distillation = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_DISTILLATION]);
      	$detail->sorties->lies = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES_LIES]);
      	$detail->stocks_fin->bloque = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE]);
      	$detail->stocks_fin->warrante = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE]);
      	$detail->stocks_fin->instance = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE]);
      	$detail->stocks_fin->commercialisable = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE]);
      	if ($detail->has_vrac) {
      		$detail->total_debut_mois_interpro = $detail->total_debut_mois;
      	}
  	}
  	
  	private function floatize($value)
  	{
  		return floatval(str_replace(',', '.', $value));
  	}
  	
  	private function checkDetailTotaux($detail)
  	{
      	$total_entrees = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_ENTREES]);
      	$total_sorties = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_SORTIES]);
      	$total = $this->floatize($this->datas[DRMDateView::VALUE_DETAIL_TOTAL]);
      	if (round($detail->total_entrees,4) != round($total_entrees,4)) {
      		if (round($detail->total_entrees,4) > 0) {
      			$this->loggeur->addCalculateColumnLog(DRMDateView::VALUE_DETAIL_ENTREES, 'drm detail total entrées', $total_entrees, $detail->total_entrees);
      		} else {
      			$detail->entrees->recolte = round($total_entrees,4);
      		}
      	}
      	/*if (round($detail->total_sorties,4) != round($total_sorties,4)) {
      		$this->loggeur->addCalculateColumnLog(DRMDateView::VALUE_DETAIL_SORTIES, 'drm detail total sorties', $total_sorties, $detail->total_sorties);
      	}
      	if (round($detail->total,4) != round($total,4)) {
      		$this->loggeur->addCalculateColumnLog(DRMDateView::VALUE_DETAIL_TOTAL, 'drm detail total fin de mois', $total, $detail->total);
      	}*/
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
  			$this->loggeur->addInvalidColumnLog($dataIndice, $dataName, $this->datas[$dataIndice]);
  			return null;
  		}
  		return ($this->datas[$dataIndice])? $this->datas[$dataIndice] : null;
  	}  
  
	public function getHashProduit() 
	{
		$hash = 'declaration/certifications/'.$this->getKey($this->datas[DRMDateView::VALUE_CERTIFICATION_CODE]).
                '/genres/'.$this->getKey($this->datas[DRMDateView::VALUE_GENRE_CODE], true).
                '/appellations/'.$this->getKey($this->datas[DRMDateView::VALUE_APPELLATION_CODE], true).
                '/mentions/'.ConfigurationProduit::DEFAULT_KEY.
                '/lieux/'.$this->getKey($this->datas[DRMDateView::VALUE_LIEU_CODE], true).
                '/couleurs/'.strtolower($this->couleurKeyToCode($this->datas[DRMDateView::VALUE_COULEUR_CODE])).
                '/cepages/'.$this->getKey($this->datas[DRMDateView::VALUE_CEPAGE_CODE], true);
		return $hash;
	}
	
	public function getEtablissement()
	{
		return 	($this->datas[DRMDateView::VALUE_IDENTIFIANT_DECLARANT])? $this->datas[DRMDateView::VALUE_IDENTIFIANT_DECLARANT] : null;
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
  	
  	public function hasErrors()
  	{
  		return $this->loggeur->hasLogs();
  	}

	public function getLogs() 
	{
    	return $this->loggeur->getLogs();
	}
}
