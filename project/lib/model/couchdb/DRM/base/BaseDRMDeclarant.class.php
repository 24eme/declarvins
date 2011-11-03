<?php
/**
 * BaseDRMDeclarant
 * 
 * Base model for DRMDeclarant

 * @property string $nom
 * @property string $siret
 * @property string $cni
 * @property string $cvi
 * @property string $no_accises
 * @property string $no_tva_intracommunautaire
 * @property sfCouchdbJson $siege
 * @property sfCouchdbJson $comptabilite
 * @property string $service_douane

 * @method string getNom()
 * @method string setNom()
 * @method string getSiret()
 * @method string setSiret()
 * @method string getCni()
 * @method string setCni()
 * @method string getCvi()
 * @method string setCvi()
 * @method string getNoAccises()
 * @method string setNoAccises()
 * @method string getNoTvaIntracommunautaire()
 * @method string setNoTvaIntracommunautaire()
 * @method sfCouchdbJson getSiege()
 * @method sfCouchdbJson setSiege()
 * @method sfCouchdbJson getComptabilite()
 * @method sfCouchdbJson setComptabilite()
 * @method string getServiceDouane()
 * @method string setServiceDouane()
 
 */

abstract class BaseDRMDeclarant extends sfCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDeclarant';
    }
                
}