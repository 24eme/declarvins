<?php
/**
 * BaseDAIDSCertification
 * 
 * Base model for DAIDSCertification

 * @property string $code
 * @property string $libelle
 * @property float $total_manquants_excedents
 * @property float $total_entrees
 * @property float $total_manquants_taxables
 * @property float $total_droits
 * @property acCouchdbJson $genres

 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method float getTotalManquantsExcedents()
 * @method float setTotalManquantsExcedents()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalManquantsTaxables()
 * @method float setTotalManquantsTaxables()
 * @method float getTotalDroits()
 * @method float setTotalDroits()
 * @method acCouchdbJson getGenres()
 * @method acCouchdbJson setGenres()
 
 */

abstract class BaseDAIDSCertification extends _DAIDSTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSCertification';
    }
                
}