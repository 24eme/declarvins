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
        if ($lock->exist($res['key']) && $lock->get($res['key']) == $res['value']) {
          
          throw new sfException('the archive value need to be different from the lock one');
        }
        $lock->add($res['key'], $res['value']); 
        $lock->save();
        break;
      }catch(sfException $e) {
      }
      sleep(1);
    }
    if ($i >= 10) {

      throw new sfException('Could not acquire the archive lock');
    }
  }
}