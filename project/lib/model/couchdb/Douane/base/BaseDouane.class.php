<?php

class BaseDouane extends sfCouchdbDocument {
    public function getDocumentDefinitionModel() {
        return 'Douane';
    }
}
