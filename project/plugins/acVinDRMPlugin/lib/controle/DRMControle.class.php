<?php
abstract class DRMControle
{
	protected $type;
	protected $code;
	protected $lien;
	protected $libelle;
	protected $messages;

	public function __construct($type, $code, $lien, $libelle = null) {
		$this->setType($type);
		$this->setCode($code);
		$this->setLien($lien);
		$this->setLibelle($libelle);
		$this->setMessages(ControlesClient::getInstance()->findAll()->getFields());
	}

	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function setCode($code)
	{
		$this->code = $code;
	}
	
	public function getLien()
	{
		return $this->lien;
	}
	
	public function setLien($lien)
	{
		$this->lien = $lien;
	}
	
	public function getLibelle()
	{
		return $this->libelle;
	}
	
	public function setLibelle($libelle)
	{
		$this->libelle = $libelle;
	}
	
	public function hasMessages() {
	  return count($this->getMessages());
	}

	public function getMessages()
	{
		return $this->messages;
	}
	
	public function getMessage()
	{
	  if (!$this->hasMessages()  || !isset($this->messages[$this->getCode()]))
	    throw new sfException('no messages for code "'.$this->getCode().'"');
	  $message = $this->messages[$this->getCode()];
	  if ($libelle = $this->getLibelle()) {
	  	$message = str_replace("%message%", $message, $libelle);
	  }
	  return $message;
	}
	
	public function setMessages($messages)
	{
	  $this->messages = $messages;
	}
	
	public function __toString()
	{
	  try {
	    return $this->getMessage();
	  }catch(sfException $e) {
	    return $this->getCode();
	  }
	}
}