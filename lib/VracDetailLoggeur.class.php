<?php

class VracDetailLoggeur extends DataLoggeur
{
	public function addEmptyColumnLog($indice, $dataName)
	{
		$this->addLog("ERREUR colonne $indice ($dataName) : cette donnée est requise.");
	}
	
	public function addInvalidColumnLog($indice, $dataName, $value)
	{
		$this->addLog("ERREUR colonne $indice ($dataName) : cette donnée n'est pas valide ($value).");
	}
	
	public function addCalculateColumnLog($indice, $dataName, $value, $correctValue)
	{
		$this->addLog("ERREUR colonne $indice ($dataName) : cette donnée n'est pas correct ($value != $correctValue).");
	}
}
