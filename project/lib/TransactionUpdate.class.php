<?php
class TransactionUpdate
{
	const VRAC_ID = 0;
	const VRAC_OIOC_STATUT = 1;
	const VRAC_OIOC_DATE = 2;

	protected $datas;
	protected $oioc;
	protected $loggeur;
	protected $logs;
	
	public function __construct(array $datas, $oioc)
	{
		$this->datas = $datas;
		$this->oioc = $oioc;
		$this->loggeur = null;
		$this->logs = array();
		$this->checkAndformatDatas();
	}
  	
  	private function checkAndformatDatas ()
  	{
  		$drms = array();
		$numLigne = 0;
  		foreach ($this->datas as $k => $datas) {
  			$numLigne++;
  			$this->loggeur = new DRMDetailLoggeur();
  			$this->datas[$k][self::VRAC_ID] = $this->getDataValue($datas, self::VRAC_ID, 'contrat identifiant', true, '/^[0-9]{11}(-(R|M)[0-9]{2})*$/');
  			$this->datas[$k][self::VRAC_OIOC_STATUT] = $this->getDataValue($datas, self::VRAC_OIOC_STATUT, 'statut', true, '/^('.OIOC::STATUT_RECEPTIONNE.'|'.OIOC::STATUT_TRAITE.')$/');
  			$this->datas[$k][self::VRAC_OIOC_DATE] = $this->datize($this->getDataValue($datas, self::VRAC_OIOC_DATE, 'date', true), self::VRAC_OIOC_DATE, 'date');
  			
  			
  			if ($this->loggeur->hasLogs()) {
  				$this->logs[] = array('ERREUR', 'FORMAT', $numLigne, implode(' - ', $this->loggeur->getLogs()));
  			} else {
  			
	  			$vrac = VracClient::getInstance()->findByNumContrat($this->datas[$k][self::VRAC_ID]);
	  			if (!$vrac) {
	  				$this->logs[] = array('ERREUR', 'FORMAT', $numLigne, "Le contrat ".$this->datas[$k][self::VRAC_ID]." n'existe pas dans la base DeclarVins");
	  			} else {
		  			if (!$vrac->exist('oioc') || ($vrac->oioc->identifiant != $this->oioc->identifiant)) {
		  				$this->logs[] = array('ERREUR', 'ACCES', $numLigne, "L'OI/OC ".$this->oioc->identifiant." n'est pas autorisé à modifier le contrat ".$this->datas[$k][self::VRAC_ID]);
		  			}
	  			}
  			}
  		}
  		
  		if (count($this->logs) > 0) { return; }
  	}
  	
  	public function getContrats()
  	{
  		$contrats = array();
  		foreach ($this->datas as $k => $datas) {
  			$vrac = VracClient::getInstance()->findByNumContrat($this->datas[$k][self::VRAC_ID]);
  			$vrac->oioc->statut = $this->datas[$k][self::VRAC_OIOC_STATUT];
  			if ($this->datas[$k][self::VRAC_OIOC_STATUT] == OIOC::STATUT_RECEPTIONNE) {
  				$vrac->oioc->date_reception = $this->datas[$k][self::VRAC_OIOC_DATE];
  			}
  			if ($this->datas[$k][self::VRAC_OIOC_STATUT] == OIOC::STATUT_TRAITE) {
  				$vrac->oioc->date_traitement = $this->datas[$k][self::VRAC_OIOC_DATE];
  			}
  			$contrats[] = $vrac;
  		}
  		return $contrats;
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

  	public function hasErrors()
  	{
  		return (count($this->logs) > 0);
  	}
  	
  	public function getLogs()
  	{
  		return $this->logs;
  	}
}