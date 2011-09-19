<?php
class Chai extends BaseChai {
  public function getInterproObj() {
    return sfCouchdbManager::getClient('Interpro')->retrieveDocumentById($this->interpro);
  }
}