<?php
abstract class DRMControle
{
	protected $code;
	protected $lien;
	protected $messages;
	
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
	  return $this->messages[$this->getCode()];
	}
	
	public function setMessages($messages)
	{
	  $this->messages = $messages;
	}
	
	public function __toString()
	{
	  try {
	    return '<a href="'.$this->getLien().'">'.$this->getMessage().'</a>';
	  }catch(sfException $e) {
	    return '<a href="'.$this->getLien().'">'.$this->getCode().'</a>';
	  }
	}
}