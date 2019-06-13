<?php

abstract class _Compte extends acVinCompte {
	public function getGecos() 
    {
      return ($this->exist('contrat') && preg_match('/^CONTRAT-[0-9]{11}$/', $this->contrat))? str_replace("CONTRAT-", "", $this->contrat) : "19700101001";
    }
}