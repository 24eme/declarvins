<?php

class MessagesClient extends acCouchdbClient {
  public static $messages = null;
  
	public static function getInstance() {
      return acCouchdbManager::getClient("Messages");
    }

  public function retrieveMessages() {
    if (!self::$messages)
      self::$messages = parent::retrieveDocumentById('MESSAGES');
    return self::$messages;
  }
  
  public function findAll() {
  	$messages = $this->retrieveMessages();
  	unset($messages['_id']);
  	unset($messages['_rev']);
  	unset($messages['type']);
  	return $messages;
  }

  public function getMessage($id) {
    try {
      return $this->retrieveMessages()->{$id};
    }catch(Exception $e) {
      return "\"".$id."\" PAS DE MESSAGE TROUVÉ !!";
    }
  }

  public function getInfos($interpro) {
      $interpro = str_replace('INTERPRO-', '', $interpro);
      if ($interpro == 'IR') {
          $m = trim($this->retrieveMessages()->info_encart_ir);
          return ($m)? $m : null;
      }
      if ($interpro == 'CIVP') {
          $m = trim($this->retrieveMessages()->info_encart_civp);
          return ($m)? $m : null;
      }
      return null;
  }
}
