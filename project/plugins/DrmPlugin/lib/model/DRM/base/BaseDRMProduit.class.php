<?php
/**
 * BaseDRMProduit
 * 
 * Base model for DRMProduit

 * @property string $hashref
 * @property acCouchdbJson $label
 * @property string $label_supplementaire
 * @property string $disponible
 * @property string $stock_vide
 * @property string $pas_de_mouvement

 * @method string getHashref()
 * @method string setHashref()
 * @method acCouchdbJson getLabel()
 * @method acCouchdbJson setLabel()
 * @method string getLabelSupplementaire()
 * @method string setLabelSupplementaire()
 * @method string getDisponible()
 * @method string setDisponible()
 * @method string getStockVide()
 * @method string setStockVide()
 * @method string getPasDeMouvement()
 * @method string setPasDeMouvement()
 
 */

abstract class BaseDRMProduit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduit';
    }
                
}