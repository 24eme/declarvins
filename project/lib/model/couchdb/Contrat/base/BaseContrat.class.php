<?php

class BaseContrat extends sfCouchdbDocument {
    public function getDocumentDefinitionModel() {
        return 'Contrat';
    }
}
