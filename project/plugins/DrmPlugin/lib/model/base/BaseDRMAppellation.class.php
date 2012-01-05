<?php
/**
 * BaseDRMAppellation
 * 
 * Base model for DRMAppellation

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property acCouchdbJson $couleurs

 * @method float getTotalStocks()
 * @method float setTotalStocks()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method acCouchdbJson getCouleurs()
 * @method acCouchdbJson setCouleurs()
 
 */

abstract class BaseDRMAppellation extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMAppellation';
    }
                
}