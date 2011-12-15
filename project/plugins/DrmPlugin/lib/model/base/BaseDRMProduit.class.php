<?php
/**
 * BaseDRMProduit
 * 
 * Base model for DRMProduit

 * @property string $appellation
 * @property string $couleur
 * @property string $denomination
 * @property string $label
 * @property string $disponible
 * @property string $stock_vide
 * @property string $pas_de_mouvement

 * @method string getAppellation()
 * @method string setAppellation()
 * @method string getCouleur()
 * @method string setCouleur()
 * @method string getDenomination()
 * @method string setDenomination()
 * @method string getLabel()
 * @method string setLabel()
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