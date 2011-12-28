<?php
/**
 * BaseDRMDetail
 * 
 * Base model for DRMDetail

 * @property acCouchdbJson $label
 * @property string $label_supplementaire
 * @property acCouchdbJson $stocks
 * @property acCouchdbJson $entrees
 * @property acCouchdbJson $sorties

 * @method acCouchdbJson getLabel()
 * @method acCouchdbJson setLabel()
 * @method string getLabelSupplementaire()
 * @method string setLabelSupplementaire()
 * @method acCouchdbJson getStocks()
 * @method acCouchdbJson setStocks()
 * @method acCouchdbJson getEntrees()
 * @method acCouchdbJson setEntrees()
 * @method acCouchdbJson getSorties()
 * @method acCouchdbJson setSorties()
 
 */

abstract class BaseDRMDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetail';
    }
                
}