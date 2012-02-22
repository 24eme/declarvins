<?php
class DRMControleWarning extends DRMControle
{
	protected $messages = array(
		'mouvement' => "Attention ... mouvement",
		'declassement' => "Attention ... declassement",
	);
	
	public function __construct($code, $lien) {
		$this->setCode($code);
		$this->setLien($lien);
		$this->setMessages($this->messages);
	}
}