<?php
/**
 * BaseDRMAppellation
 * 
 * Base model for DRMAppellation

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property float $total_cvo
 * @property float $total_douane
 * @property acCouchdbJson $droits
 * @property acCouchdbJson $lieux

 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method float getTotalCvo()
 * @method float setTotalCvo()
 * @method float getTotalDouane()
 * @method float setTotalDouane()
 * @method acCouchdbJson getDroits()
 * @method acCouchdbJson setDroits()
 * @method acCouchdbJson getLieux()
 * @method acCouchdbJson setLieux()
 
 */

abstract class BaseDRMAppellation extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMAppellation';
    }
                
}