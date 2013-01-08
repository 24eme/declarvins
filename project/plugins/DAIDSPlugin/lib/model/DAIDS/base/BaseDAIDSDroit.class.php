<?php
/**
 * BaseDAIDSDroit
 * 
 * Base model for DAIDSDroit

 * @property integer $volume_taxe
 * @property float $taux
 * @property string $code
 * @property string libelle
 * @property float $total

 * @method integer getVolumeTaxe()
 * @method integer setVolumeTaxe()
 * @method float getTaux()
 * @method float setTaux()
 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method float getTotal()
 * @method float setTotal()
 
 */

abstract class BaseDAIDSDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSDroit';
    }
                
}