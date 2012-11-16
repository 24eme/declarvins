<?php
/**
 * BaseDAIDSDetailStocks
 * 
 * Base model for DAIDSDetailStocks

 * @property float $chais
 * @property float $propriete_tiers
 * @property float $tiers

 * @method float getChais()
 * @method float setChais()
 * @method float getProprieteTiers()
 * @method float setProprieteTiers()
 * @method float getTiers()
 * @method float setTiers()
 
 */

abstract class BaseDAIDSDetailStocks extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSDetailStocks';
    }
                
}