<?php

class Lock extends BaseLock {

  public static function getInstance() {
    $lock = acCouchdbManager::getClient()->find('Lock');
    if ($lock) {
      return $lock;
    }
    $lock = new Lock();
    $lock->_id = 'Lock';
    $lock->type = 'Lock';
    return $lock;
  }
}