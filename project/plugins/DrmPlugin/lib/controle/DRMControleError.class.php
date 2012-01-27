<?php
class DRMControleError extends DRMControle
{
	protected $messages = array(
		'vrac' => "Erreur ... vrac",
		'total_negatif' => "Erreur ... le total est négatif",
		'total_stocks' => "Erreur ... le total est supérieur aux stocks bloqué et en instance"
	);
	
	public function __construct($code, $lien) {
		$this->setCode($code);
		$this->setLien($lien);
		$this->setMessages($this->messages);
	}
	
}