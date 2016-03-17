<?php
/**
 * BaseDRMCrd
 * 
 * Base model for DRMCrd

 * @property string $libelle
 * @property acCouchdbJson $categorie
 * @property acCouchdbJson $type
 * @property acCouchdbJson $centilisation
 * @property acCouchdbJson $entrees
 * @property acCouchdbJson $sorties
 * @property float $total_debut_mois
 * @property float $total_fin_mois

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getCategorie()
 * @method acCouchdbJson setCategorie()
 * @method acCouchdbJson getType()
 * @method acCouchdbJson setType()
 * @method acCouchdbJson getCentilisation()
 * @method acCouchdbJson setCentilisation()
 * @method acCouchdbJson getEntrees()
 * @method acCouchdbJson setEntrees()
 * @method acCouchdbJson getSorties()
 * @method acCouchdbJson setSorties()
 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalFinMois()
 * @method float setTotalFinMois()
 
 */

abstract class BaseDRMCrd extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCrd';
    }
                
}