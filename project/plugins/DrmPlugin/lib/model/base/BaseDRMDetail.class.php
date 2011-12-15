<?php
/**
 * BaseDRMDetail
 * 
 * Base model for DRMDetail

 * @property string $denomination
 * @property string $label
 * @property acCouchdbJson $stocks
 * @property acCouchdbJson $entrees
 * @property acCouchdbJson $sorties
 * @property float $total

 * @method string getDenomination()
 * @method string setDenomination()
 * @method string getLabel()
 * @method string setLabel()
 * @method acCouchdbJson getStocks()
 * @method acCouchdbJson setStocks()
 * @method acCouchdbJson getEntrees()
 * @method acCouchdbJson setEntrees()
 * @method acCouchdbJson getSorties()
 * @method acCouchdbJson setSorties()
 * @method float getTotal()
 * @method float setTotal()
 
 */

abstract class BaseDRMDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetail';
    }
                
}