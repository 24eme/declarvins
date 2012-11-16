<?php
/**
 * BaseDAIDSCouleur
 * 
 * Base model for DAIDSCouleur

 * @property string $code
 * @property string $libelle
 * @property float $total_manquants_excedents
 * @property float $total_pertes_autorisees
 * @property float $total_manquants_taxables
 * @property float $total_droits
 * @property acCouchdbJson $cepages

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
 * @method float getTotalDroits()
 * @method float setTotalDroits()
 * @method acCouchdbJson getCepages()
 * @method acCouchdbJson setCepages()
 
 */

abstract class BaseDAIDSCouleur extends _DAIDSTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSCouleur';
    }
                
}