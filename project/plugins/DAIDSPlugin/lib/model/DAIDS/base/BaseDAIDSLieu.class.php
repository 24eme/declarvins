<?php
/**
 * BaseDAIDSLieu
 * 
 * Base model for DAIDSLieu

 * @property string $code
 * @property string $libelle
 * @property float $total_manquants_excedents
 * @property float $total_pertes_autorisees
 * @property float $total_manquants_taxables
 * @property float $total_douane
 * @property float $total_cvo
 * @property acCouchdbJson $couleurs

 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method float getTotalManquantsExcedents()
 * @method float setTotalManquantsExcedents()
 * @method float getTotalPertesAutorisees()
 * @method float setTotalPertesAutorisees()
 * @method float getTotalManquantsTaxables()
 * @method float setTotalManquantsTaxables()
 * @method float getTotalDouane()
 * @method float setTotalDouane()
 * @method float getTotalCvo()
 * @method float setTotalCvo()
 * @method acCouchdbJson getCouleurs()
 * @method acCouchdbJson setCouleurs()
 
 */

abstract class BaseDAIDSLieu extends _DAIDSTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSLieu';
    }
                
}