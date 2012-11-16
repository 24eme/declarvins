<?php
/**
 * BaseDAIDSStockMoyen
 * 
 * Base model for DAIDSStockMoyen

 * @property float $taux
 * @property float $volume
 * @property string $total

 * @method float getTaux()
 * @method float setTaux()
 * @method float getVolume()
 * @method float setVolume()
 * @method string getTotal()
 * @method string setTotal()
 
 */

abstract class BaseDAIDSStockMoyen extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSStockMoyen';
    }
                
}