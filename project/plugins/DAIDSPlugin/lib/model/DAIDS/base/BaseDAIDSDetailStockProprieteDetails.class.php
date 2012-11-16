<?php
/**
 * BaseDAIDSDetailStockProprieteDetails
 * 
 * Base model for DAIDSDetailStockProprieteDetails

 * @property float $reserve
 * @property float $vrac_vendu
 * @property float $vrac_libre
 * @property float $conditionne

 * @method float getReserve()
 * @method float setReserve()
 * @method float getVracVendu()
 * @method float setVracVendu()
 * @method float getVracLibre()
 * @method float setVracLibre()
 * @method float getConditionne()
 * @method float setConditionne()
 
 */

abstract class BaseDAIDSDetailStockProprieteDetails extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSDetailStockProprieteDetails';
    }
                
}