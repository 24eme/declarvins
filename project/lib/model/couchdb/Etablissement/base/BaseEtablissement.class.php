<?php

/**
 * 
 * @property string $intepro
 * @property string $compte
 * @property string $contrat
 * @property sfCouchdbJson $droits
 * @property string $num_interne
 * @property string $siret
 * @property string $cni
 * @property string $cvi
 * @property string $no_accises
 * @property string $no_tva_intracommunautaire    
 * @property string $famille
 * @property string $sous_famille
 * @property string $raison_social
 * @property string $email
 * @property string $telephone
 * @property string $fax
 * @property sfCouchdbJson $siege
 * @property sfCouchdbJson $comptablilite
 * @property string $service_douane
 * 
 */

class BaseEtablissement extends sfCouchdbDocument {
    public function getDocumentDefinitionModel() {
        return 'Etablissement';
    }
}
