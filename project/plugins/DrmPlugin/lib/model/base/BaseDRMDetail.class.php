<?php
/**
 * BaseDRMDetail
 * 
 * Base model for DRMDetail

 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property acCouchdbJson $label
 * @property string $label_supplementaire
 * @property acCouchdbJson $vrac
 * @property acCouchdbJson $stocks
 * @property acCouchdbJson $entrees
 * @property acCouchdbJson $sorties

 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method acCouchdbJson getLabel()
 * @method acCouchdbJson setLabel()
 * @method string getLabelSupplementaire()
 * @method string setLabelSupplementaire()
 * @method acCouchdbJson getVrac()
 * @method acCouchdbJson setVrac()
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