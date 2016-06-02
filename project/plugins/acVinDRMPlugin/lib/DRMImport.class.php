<?php

class DRMImport
{
	protected $datas;
	protected $etablissement;
	protected $client;
	protected $loggeur;
	protected $logs;
	public static $annexes_possibles = array(
			'defaut_apurement',
			'daa-debut',
			'daa-fin',
			'dsa-debut',
			'dsa-fin',
			'adhesion_emcs_gamma',
			'paiement-douane-frequence',
			'paiement-douane-moyen',
			'caution-dispense',
			'caution-numero',
			'caution-organisme'
	);
  
	public function __construct(array $datas, $etablissement) 
	{
		$this->datas = $datas;
		$this->etablissement = $etablissement;
		$this->loggeur = null;
		$this->client = DRMClient::getInstance();
		$this->logs = array();
		$this->checkAndformatDatas();
	}
  	
  	private function checkAndformatDatas ()
  	{
  		$drms = array();
		$numLigne = 0;
  		$configuration = ConfigurationClient::getCurrent();
  		foreach ($this->datas as $k => $datas) {
  			$numLigne++;
  			$this->loggeur = new DRMDetailLoggeur();
  			$this->datas[$k][DRMDateView::VALUE_TYPE] = $this->getDataValue($datas, DRMDateView::VALUE_TYPE, 'ligne type', true, '/^(DETAIL|CONTRAT|ANNEXE)$/');
  			$this->datas[$k][DRMDateView::VALUE_IDENTIFIANT_DECLARANT] = $this->getDataValue($datas, DRMDateView::VALUE_IDENTIFIANT_DECLARANT, 'drm identifiant declarant', true);
  			$this->datas[$k][DRMDateView::VALUE_ANNEE] = $this->getDataValue($datas, DRMDateView::VALUE_ANNEE, 'drm année', true, '/^[0-9]{4}$/');
  			$this->datas[$k][DRMDateView::VALUE_MOIS] = $this->getDataValue($datas, DRMDateView::VALUE_MOIS, 'drm mois', true, '/^[0-9]{1,2}$/');
  			$this->datas[$k][DRMDateView::VALUE_MOIS] = ($this->datas[$k][DRMDateView::VALUE_MOIS])? sprintf("%02d", $this->datas[$k][DRMDateView::VALUE_MOIS]) : null;
  			$this->datas[$k][DRMDateView::VALUE_VERSION] = $this->getDataValue($datas, DRMDateView::VALUE_VERSION, 'drm version', false, '/^[RM]{1}[0-9]{2}$/');
  			$this->datas[$k][DRMDateView::VALUE_IDIVSE] = $this->getDataValue($datas, DRMDateView::VALUE_IDIVSE, 'drm ivse id');
  			$this->datas[$k][DRMDateView::VALUE_IDDRM] = $this->getDataValue($datas, DRMDateView::VALUE_IDDRM, 'drm historique id');
  			$this->datas[$k][DRMDateView::VALUE_ANNEE_PRECEDENTE] = $this->getDataValue($datas, DRMDateView::VALUE_ANNEE_PRECEDENTE, 'drm année précédente', false, '/^[0-9]{4}$/');
  			$this->datas[$k][DRMDateView::VALUE_MOIS_PRECEDENTE] = $this->getDataValue($datas, DRMDateView::VALUE_MOIS_PRECEDENTE, 'drm mois précédente', false, '/^[0-9]{1,2}$/');
  			$this->datas[$k][DRMDateView::VALUE_MOIS_PRECEDENTE] = ($this->datas[$k][DRMDateView::VALUE_MOIS_PRECEDENTE])? sprintf("%02d", $this->datas[$k][DRMDateView::VALUE_MOIS_PRECEDENTE]) : null;
  			$this->datas[$k][DRMDateView::VALUE_VERSION_PRECEDENTE] = $this->getDataValue($datas, DRMDateView::VALUE_VERSION_PRECEDENTE, 'drm version précédente', false, '/^[RM]{1}[0-9]{2}$/');
  			$this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO] = $this->getDataValue($datas, DRMDateView::VALUE_CONTRAT_NUMERO, 'drm contrat numéro');
  			$this->datas[$k][DRMDateView::VALUE_LABELS] = $this->getDataValue($datas, DRMDateView::VALUE_LABELS, 'drm detail labels');
  			$this->datas[$k][DRMDateView::VALUE_DATEDESIGNATURE] = $this->datize($this->getDataValue($datas, DRMDateView::VALUE_DATEDESIGNATURE, 'drm date de signature'), DRMDateView::VALUE_DATEDESIGNATURE, 'drm date de signature');
  			$this->datas[$k][DRMDateView::VALUE_DATEDESAISIE] = $this->datize($this->getDataValue($datas, DRMDateView::VALUE_DATEDESAISIE, 'drm date de saisie'), DRMDateView::VALUE_DATEDESAISIE, 'drm date de saisie');
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_ENTREES_ACHAT] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_ENTREES_ACHAT]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_ENTREES_REPLI] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_ENTREES_REPLI]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_ENTREES_CRD] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_ENTREES_CRD]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_VRAC] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_VRAC]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_EXPORT] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_EXPORT]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_FACTURES] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_FACTURES]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_CRD] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_CRD]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_PERTES] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_PERTES]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_REPLI] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_REPLI]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_DISTILLATION] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_DISTILLATION]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_SORTIES_LIES] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_SORTIES_LIES]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_TOTAL] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_TOTAL]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE]);
  			$this->datas[$k][DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE] = $this->floatize($datas[DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE]);
  			$this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME] = ($datas[DRMDateView::VALUE_CONTRAT_VOLUME])? trim($datas[DRMDateView::VALUE_CONTRAT_VOLUME]) : null;
  			$this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO] = ($datas[DRMDateView::VALUE_CONTRAT_NUMERO])? trim($datas[DRMDateView::VALUE_CONTRAT_NUMERO]) : null;
  			$drmId = $this->client->buildId($this->datas[$k][DRMDateView::VALUE_IDENTIFIANT_DECLARANT], $this->client->buildPeriode($this->datas[$k][DRMDateView::VALUE_ANNEE], $this->datas[$k][DRMDateView::VALUE_MOIS]), $this->datas[$k][DRMDateView::VALUE_VERSION]);
  			$drms[$drmId] = $drmId;
  			
  			if ($this->loggeur->hasLogs()) {
  				$this->logs[] = array('ERREUR', 'FORMAT', $numLigne, implode(' - ', $this->loggeur->getLogs()));
  			}
  			
  			if ($this->datas[$k][DRMDateView::VALUE_TYPE] != 'ANNEXE') {
	  			$hash = $this->getHashProduit($this->datas[$k]);
	  			if (!$configuration->getConfigurationProduit($hash)) {
	  				$this->logs[] = array('ERREUR', 'FORMAT', $numLigne, "Le produit ".$hash." n'existe pas dans la base DeclarVins");
	  			}
  			}
  			
  			if ($this->datas[$k][DRMDateView::VALUE_TYPE] == 'CONTRAT') {
  				if (!$this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO] || !$this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME]) {
  					$this->logs[] = array('ERREUR', 'CONTRAT', $numLigne, "Le numéro et le volume du contrat doivent être renseignés");
  				} else {
	  				if (!VracClient::getInstance()->findByNumContrat($this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO])) {
	  					$this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME] = null;
	  					$this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO] = null;
	  					//$this->logs[] = array('ERREUR', 'CONTRAT', $numLigne, "Le contrat numéro ".$this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO]." n'existe pas dans la base DeclarVins");
	  				}
  				}
  			}
  			
  			if ($this->datas[$k][DRMDateView::VALUE_TYPE] == 'ANNEXE') {
  				if (!in_array($this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO], self::$annexes_possibles)) {
  					$this->logs[] = array('ERREUR', 'ANNEXE', $numLigne, "Identifiant d'annexe inconnu");
  				} else {
  					if ($this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO] == 'paiement-douane-frequence') {
  						$tab = array(DRMPaiement::FREQUENCE_ANNUELLE, DRMPaiement::FREQUENCE_MENSUELLE);
	  					if (!in_array($this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME], $tab)) {
	  						$this->logs[] = array('ERREUR', 'ANNEXE', $numLigne, "Valeur non autorisée");
	  					}
  					}
  					if ($this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO] == 'paiement-douane-moyen') {
  						$tab = array('Numéraire', 'Chèque', 'Virement');
	  					if (!in_array($this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME], $tab)) {
	  						$this->logs[] = array('ERREUR', 'ANNEXE', $numLigne, "Valeur non autorisée");
	  					}
  					}

  					if (!in_array($this->datas[$k][DRMDateView::VALUE_CONTRAT_NUMERO], array('paiement-douane-frequence', 'paiement-douane-moyen', 'caution-numero', 'caution-organisme'))) {
  						$this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME] = ($this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME])? $this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME] : 0;
  						if (!is_numeric($this->datas[$k][DRMDateView::VALUE_CONTRAT_VOLUME])) {
  							$this->logs[] = array('ERREUR', 'ANNEXE', $numLigne, "Valeur non valide : integer attendu");
  						}
  					}
  				}
  			}
  		}
  		
  		if (count($this->logs) > 0) { return; }
  		
  		if (count($drms) != 1) {
  			$this->logs[] = array('ERREUR', 'DRM', null, "l'import exige une seule DRM par fichier");
  		}
  		
  		if (count($this->logs) > 0) { return; }
  		
  		$drmId = current($drms);
  		
  		$etablissementId = $this->client->getEtablissementByDRMId($drmId);
  		if ($etablissementId != $this->etablissement->identifiant) {
  			$this->logs[] = array('ERREUR', 'ACCES', null, "Import restreint à l'établissement ".$this->etablissement->identifiant);
  		}
  		if (!$this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
  			$this->logs[] = array('ERREUR', 'ACCES', null, "L'établissement ".$this->etablissement->identifiant." n'est pas autorisé à déclarer des DRMs");
  		}
  		
  		if (count($this->logs) > 0) { return; }
  		
  		if ($drm = $this->client->find($drmId)) {
  			$this->logs[] = array('ERREUR', 'DRM', null, "La DRM $drmId est déjà existante dans la base DeclarVins");
  		} elseif ($version = $this->client->getVersionByDRMId($drmId)) {
  			$versionType = substr($version, 0, 1);
  			$versionNum = substr($version, -2);
  			if ($versionNum == 1) {
  				if (!$this->client->find(substr($drmId, 0, -4))) {
  					$this->logs[] = array('ERREUR', 'DRM', null, "La DRM $drmId ne peut être importée car elle correspond à la rectification / modification d'une DRM inexistante dans la base DeclarVins");
  				}
  			} else {
  				if (!$this->client->find(substr($drmId, 0, -4).sprintf("-R%02d", $versionNum - 1)) && !$this->client->find(substr($drmId, 0, -4).sprintf("-M%02d", $versionNum - 1))) {
  					$this->logs[] = array('ERREUR', 'DRM', null, "La DRM $drmId ne peut être importée car elle correspond à la rectification / modification d'une DRM inexistante dans la base DeclarVins");
  				}
  			}
  		}
  	}
  	
  	public function getDRM()
  	{
  		$drm = null;
  		foreach ($this->datas as $datas) {
  			if (!$drm) {
  				$drm = $this->parseDrm($datas);
  			}
  			switch($datas[DRMDateView::VALUE_TYPE]) {
  				case 'DETAIL':
  					$hash = $this->getHashProduit($datas);
  					$detail = $drm->addProduit($hash, explode('|', $datas[DRMDateView::VALUE_LABELS]));
  					$this->parseDetail($detail, $datas);
  					break;
  				case 'CONTRAT':
  					$hash = $this->getHashProduit($datas);
  					$detail = $drm->addProduit($hash, explode('|', $datas[DRMDateView::VALUE_LABELS]));
  					$this->parseContrat($detail, $datas);
  					break;
  				case 'ANNEXE':
  					$this->parseAnnexe($drm, $datas);
  					break;
  				default:
  					break;
  			}
  		}
  		$this->validateDrm($drm);
  		return $drm;
  	}
  	
  	private function validateDrm($drm)
  	{
  		$validation = new DRMValidation($drm);
  		if (!$validation->isValide()) {
  			$this->logs[] = array('ERREUR', 'DRM', null, "DRM non valide : ".implode(" // ", $validation->getErrors()));
  		}
  	}

  	private function parseAnnexe($drm, $datas)
  	{
  		$hash = str_replace('-', '/', $datas[DRMDateView::VALUE_CONTRAT_NUMERO]);
  		$value = ($datas[DRMDateView::VALUE_CONTRAT_VOLUME])? $datas[DRMDateView::VALUE_CONTRAT_VOLUME] : null;
  		if (!in_array($datas[DRMDateView::VALUE_CONTRAT_NUMERO], array('paiement-douane-frequence', 'paiement-douane-moyen', 'caution-numero', 'caution-organisme'))) {
  			$value = intval($value);
  		}
  		$drm->declaratif->set($hash, $value);
  	}

  	private function parseContrat($detail, $datas)
  	{
  		$numContrat = $datas[DRMDateView::VALUE_CONTRAT_NUMERO];
  		$volContrat = $this->floatize($datas[DRMDateView::VALUE_CONTRAT_VOLUME]);
  		if ($numContrat) {
  			$detail->addVrac($numContrat, $volContrat);
  		}
  	}
  	
  	private function parseDetail($detail, $datas)
  	{
  		$detail->total_debut_mois = $datas[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS];
  		$detail->stocks_debut->bloque = $datas[DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE];
  		$detail->stocks_debut->warrante = $datas[DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE];
  		$detail->stocks_debut->instance = $datas[DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE];
  		$detail->stocks_debut->commercialisable = $datas[DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE];
  		$detail->entrees->achat = $datas[DRMDateView::VALUE_DETAIL_ENTREES_ACHAT];
  		$detail->entrees->recolte = $datas[DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE];
  		$detail->entrees->repli = $datas[DRMDateView::VALUE_DETAIL_ENTREES_REPLI];
  		$detail->entrees->declassement = $datas[DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT];
  		$detail->entrees->mouvement = $datas[DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT];
  		$detail->entrees->crd = $datas[DRMDateView::VALUE_DETAIL_ENTREES_CRD];
  		$detail->sorties->vrac = $datas[DRMDateView::VALUE_DETAIL_SORTIES_VRAC];
  		$detail->sorties->export = $datas[DRMDateView::VALUE_DETAIL_SORTIES_EXPORT];
  		$detail->sorties->factures = $datas[DRMDateView::VALUE_DETAIL_SORTIES_FACTURES];
  		$detail->sorties->crd = $datas[DRMDateView::VALUE_DETAIL_SORTIES_CRD];
  		$detail->sorties->consommation = $datas[DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION];
  		$detail->sorties->pertes = $datas[DRMDateView::VALUE_DETAIL_SORTIES_PERTES];
  		$detail->sorties->declassement = $datas[DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT];
  		$detail->sorties->repli = $datas[DRMDateView::VALUE_DETAIL_SORTIES_REPLI];
  		$detail->sorties->mouvement = $datas[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT];
  		$detail->sorties->distillation = $datas[DRMDateView::VALUE_DETAIL_SORTIES_DISTILLATION];
  		$detail->sorties->lies = $datas[DRMDateView::VALUE_DETAIL_SORTIES_LIES];
  		$detail->total = $datas[DRMDateView::VALUE_DETAIL_TOTAL];
  		$detail->stocks_fin->bloque = $datas[DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE];
  		$detail->stocks_fin->warrante = $datas[DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE];
  		$detail->stocks_fin->instance = $datas[DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE];
  		$detail->stocks_fin->commercialisable = $datas[DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE];
  		$detail->total_debut_mois_interpro = $detail->total_debut_mois;
  	}
  	
  	private function parseDrm($datas)
  	{
  		$drmDeclarant = $datas[DRMDateView::VALUE_IDENTIFIANT_DECLARANT];
  		$drmAnnee = $datas[DRMDateView::VALUE_ANNEE];
  		$drmMois = $datas[DRMDateView::VALUE_MOIS];
  		$drmVersion = $datas[DRMDateView::VALUE_VERSION];
  		$drmPeriode = $this->client->buildPeriode($drmAnnee, $drmMois);
  		$drmId = $this->client->buildId($drmDeclarant, $drmPeriode, $drmVersion);

  		$drm = $this->client->createBlankDoc($drmDeclarant, $drmPeriode, $drmVersion);
		$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_EDI;
  		$drm->identifiant_ivse = $datas[DRMDateView::VALUE_IDIVSE];
  		$drm->identifiant_drm_historique = $datas[DRMDateView::VALUE_IDDRM];
  	
  		$drmPrecedenteAnnee = $datas[DRMDateView::VALUE_ANNEE_PRECEDENTE];
  		$drmPrecedenteMois = $datas[DRMDateView::VALUE_MOIS];
  		$drmPrecedenteVersion = $datas[DRMDateView::VALUE_VERSION_PRECEDENTE];
  		$drm->precedente = ($drmPrecedenteAnnee && $drmPrecedenteMois)? $this->client->buildId($drmDeclarant, $this->client->buildPeriode($drmPrecedenteAnnee, $drmPrecedenteMois), $drmPrecedenteVersion) : null;

  		return $drm;
  	}

  	public function hasErrors()
  	{
  		return (count($this->logs) > 0);
  	}
  	
  	public function getLogs()
  	{
  		return $this->logs;
  	}

  	private function getDataValue($datas, $dataIndice, $dataName, $required = false, $regexp = null)
  	{
  		if ($datas[$dataIndice] == " ") {
  			$datas[$dataIndice] = null;
  		}
  		if ($required && !$datas[$dataIndice]) {
  			$this->loggeur->addEmptyColumnLog($dataIndice, $dataName);
  			return null;
  		}
  		if (!empty($datas[$dataIndice]) && $regexp && !preg_match($regexp, $datas[$dataIndice])) {
  			$this->loggeur->addInvalidColumnLog($dataIndice, $dataName, $datas[$dataIndice]);
  			return null;
  		}
  		return ($datas[$dataIndice])? $datas[$dataIndice] : null;
  	}
  	


  	private function floatize($value)
  	{
  		if ($value === null) {
  			return null;
  		}
  		return floatval(str_replace(',', '.', $value));
  	}


  	private function datize($str, $dataIndice, $dataName)
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
  		$this->loggeur->addInvalidColumnLog($dataIndice, $dataName, $this->datas[$dataIndice]);
  	}

  	private function getHashProduit($datas)
  	{
  		$hash = 'declaration/certifications/'.$this->getKey($datas[DRMDateView::VALUE_CERTIFICATION_CODE]).
  		'/genres/'.$this->getKey($datas[DRMDateView::VALUE_GENRE_CODE], true).
  		'/appellations/'.$this->getKey($datas[DRMDateView::VALUE_APPELLATION_CODE], true).
  		'/mentions/'.ConfigurationProduit::DEFAULT_KEY.
  		'/lieux/'.$this->getKey($datas[DRMDateView::VALUE_LIEU_CODE], true).
  		'/couleurs/'.strtolower($this->couleurKeyToCode($datas[DRMDateView::VALUE_COULEUR_CODE])).
  		'/cepages/'.$this->getKey($datas[DRMDateView::VALUE_CEPAGE_CODE], true);
  		return $hash;
  	}
  	
  	private function getKey($key, $withDefault = false)
  	{
  		if ($key == " " || !$key) {
  			$key = null;
  		}
  		if ($withDefault) {
  			return ($key)? $key : ConfigurationProduit::DEFAULT_KEY;
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
}
