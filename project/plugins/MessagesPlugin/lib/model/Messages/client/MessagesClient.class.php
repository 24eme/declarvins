<?php

class MessagesClient extends acCouchdbClient {
  public static $messages = null;

  public function retrieveMessages() {
    if (!self::$messages)
      self::$messages = parent::retrieveDocumentById('MESSAGES');
    return self::$messages;
  }

  public function getMessage($id) {
    try {
      return $this->retrieveMessages()->{$id};
    }catch(Exception $e) {
      return "\"".$id."\" PAS DE MESSAGE TROUVÉ !!";
    }
  }
}
