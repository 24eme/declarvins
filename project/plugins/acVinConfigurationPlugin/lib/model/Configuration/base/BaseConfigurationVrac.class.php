<?php
/**
 * BaseConfigurationVrac
 * 
 * Base model for ConfigurationVrac

 * @property ConfigurationVracEtapes $etapes
 * @property acCouchdbJson $vendeur_types
 * @property acCouchdbJson $acheteur_types
 * @property acCouchdbJson $types_transaction
 * @property acCouchdbJson $labels
 * @property acCouchdbJson $mentions
 * @property acCouchdbJson $types_prix
 * @property acCouchdbJson $conditions_paiement
 * @property acCouchdbJson $types_contrat
 * @property acCouchdbJson $cvo_natures
 * @property acCouchdbJson $cvo_repartitions
 * @property acCouchdbJson $natures_document
 * @property acCouchdbJson $types_domaine
 * @property acCouchdbJson $delais_paiement
 * @property acCouchdbJson $contenances

 * @method ConfigurationVracEtapes getEtapes()
 * @method ConfigurationVracEtapes setEtapes()
 * @method acCouchdbJson getVendeurTypes()
 * @method acCouchdbJson setVendeurTypes()
 * @method acCouchdbJson getAcheteurTypes()
 * @method acCouchdbJson setAcheteurTypes()
 * @method acCouchdbJson getTypesTransaction()
 * @method acCouchdbJson setTypesTransaction()
 * @method acCouchdbJson getLabels()
 * @method acCouchdbJson setLabels()
 * @method acCouchdbJson getMentions()
 * @method acCouchdbJson setMentions()
 * @method acCouchdbJson getTypesPrix()
 * @method acCouchdbJson setTypesPrix()
 * @method acCouchdbJson getConditionsPaiement()
 * @method acCouchdbJson setConditionsPaiement()
 * @method acCouchdbJson getTypesContrat()
 * @method acCouchdbJson setTypesContrat()
 * @method acCouchdbJson getCvoNatures()
 * @method acCouchdbJson setCvoNatures()
 * @method acCouchdbJson getCvoRepartitions()
 * @method acCouchdbJson setCvoRepartitions()
 * @method acCouchdbJson getNaturesDocument()
 * @method acCouchdbJson setNaturesDocument()
 * @method acCouchdbJson getTypesDomaine()
 * @method acCouchdbJson setTypesDomaine()
 * @method acCouchdbJson getDelaisPaiement()
 * @method acCouchdbJson setDelaisPaiement()
 * @method acCouchdbJson getContenances()
 * @method acCouchdbJson setContenances()
 
 */

abstract class BaseConfigurationVrac extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationVrac';
    }
                
}