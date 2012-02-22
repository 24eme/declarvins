<?php
class DRMControleError extends DRMControle
{
	protected $messages = array(
		'vrac' => "Erreur ... vrac",
		'total_negatif' => "Erreur ... le total est négatif",
		'total_stocks' => "Erreur ... le total est inférieur aux stocks bloqué et en instance",
		'repli' => "Erreur ... la somme des replis en entrée est différente de celle en sortie"
	);
	
	public function __construct($code, $lien) {
		$this->setCode($code);
		$this->setLien($lien);
		$this->setMessages($this->messages);
	}
	
}