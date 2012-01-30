<?php
class DRMControleEngagement extends DRMControle
{
	protected $messages = array(
		'export' => "Je m'engage à ... pour l'export",
		'declassement' => "Je m'engage à ... pour le déclassement",
		'repli' => "Je m'engage à ... pour le repli"
	);
	
	public function __construct($code) {
		$this->setCode($code);
		$this->setLien(null);
		$this->setMessages($this->messages);
	}
	
	public function __toString()
	{
		if ($messages = $this->getMessages()) {
			return (isset($messages[$this->getCode()]))? $messages[$this->getCode()] : $this->getCode();
		} else {
			return $this->getCode();
		}
	}
	
}