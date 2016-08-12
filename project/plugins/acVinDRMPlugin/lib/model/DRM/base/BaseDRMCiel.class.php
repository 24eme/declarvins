<?php
/**
 * BaseDRMCiel
 * 
 * Base model for DRMCiel

 * @property integer $transfere
 * @property string $xml
 * @property string $identifiant_declaration
 * @property string $horodatage_depot

 * @method integer getTransfere()
 * @method integer setTransfere()
 * @method string getXml()
 * @method string setXml()
 * @method string getIdentifiantDeclaration()
 * @method string setIdentifiantDeclaration()
 * @method string getHorodatageDepot()
 * @method string setHorodatageDepot()

 */

abstract class BaseDRMCiel extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCiel';
    }
                
}