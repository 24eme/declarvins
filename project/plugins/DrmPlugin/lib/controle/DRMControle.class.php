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
	
	public function getMessages()
	{
		return $this->messages;
	}
	
	public function setMessages($messages)
	{
		$this->messages = $messages;
	}
	
	public function __toString()
	{
		if ($messages = $this->getMessages()) {
			return (isset($messages[$this->getCode()]))? '<a href="'.$this->getLien().'">'.$messages[$this->getCode()].'</a>' : '<a href="'.$this->getLien().'">'.$this->getCode().'</a>';
		} else {
			return '<a href="'.$this->getLien().'">'.$this->getCode().'</a>';
		}
	}
}