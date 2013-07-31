<?php

class Lock extends BaseLock {

  protected static function getInstance() {
    $lock = acCouchdbManager::getClient()->find('Lock');
    if ($lock) {
      return $lock;
    }
    $lock = new Lock();
    $lock->_id = 'Lock';
    $lock->type = 'Lock';
    return $lock;
  }

  public static function runLock($ilockobject, $args =  null) {
    for($i = 0 ; $i < 10 ; $i++) {
      try {
        $lock = Lock::getInstance();
        $res = $ilockobject->executeLock($args);
        if (!isset($res['key'])) {

          throw new sfException('executeLock needs to return a hashtable with an element "key"');
        }
        if (!isset($res['value'])) {

          throw new sfException('executeLock needs to return a hashtable with an element "value"');
        }
        if($lock->exist($res['key']) && $lock->get($res['key']) == $res['value']) {
          $docid = preg_replace('/^.*:/', '', $lock->get($res['value']));
          if(acCouchdbManager::getClient()->find($docid, acCouchdbClient::HYDRATE_JSON)) {
            throw new sfException('the archive value need to be different from the lock one');
          }
        }
        $lock->add($res['key'], $res['value'].':'.$res['docid']); 
        $lock->save();
        break;
      }catch(sfException $e) {
      }
      sleep(1);
    }
    if ($i >= 10) {

      throw new sfException("Could not acquire the archive lock, il faut s'assurer que le numéro d'archive de lock est égal au plus élévé de celui des vues");
    }
  }
}