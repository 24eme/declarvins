<?php
/**
 * BaseDAIDSStocksMoyen
 * 
 * Base model for DAIDSStocksMoyen

 * @property DAIDSStockMoyen $vinifie
 * @property DAIDSStockMoyen $non_vinifie
 * @property DAIDSStockMoyen $conditionne

 * @method DAIDSStockMoyen getVinifie()
 * @method DAIDSStockMoyen setVinifie()
 * @method DAIDSStockMoyen getNonVinifie()
 * @method DAIDSStockMoyen setNonVinifie()
 * @method DAIDSStockMoyen getConditionne()
 * @method DAIDSStockMoyen setConditionne()
 
 */

abstract class BaseDAIDSStocksMoyen extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSStocksMoyen';
    }
                
}