<?php
/**
 * BaseDRMCertification
 * 
 * Base model for DRMCertification

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property DRMAppellation $appellations

 * @method float getTotalStocks()
 * @method float setTotalStocks()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method DRMAppellation getAppellations()
 * @method DRMAppellation setAppellations()
 
 */

abstract class BaseDRMCertification extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCertification';
    }
                
}