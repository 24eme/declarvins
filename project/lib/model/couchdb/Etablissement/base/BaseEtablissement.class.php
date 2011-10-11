<?php

class BaseEtablissement extends sfCouchdbDocument {
    public function getDocumentDefinitionModel() {
        return 'Etablissement';
    }
}
