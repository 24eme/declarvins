<?php
/**
 * BaseDRMDeclaration
 * 
 * Base model for DRMDeclaration

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property acCouchdbJson $certifications

 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method acCouchdbJson getCertifications()
 * @method acCouchdbJson setCertifications()
 
 */

abstract class BaseDRMDeclaration extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDeclaration';
    }
                
}