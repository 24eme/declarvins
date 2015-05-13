<?php
/**
 * BaseDRMMention
 * 
 * Base model for DRMMention

 * @property string $code
 * @property string $libelle
 * @property string $selecteur
 * @property float $total_debut_mois
 * @property float $total_entrees
 * @property float $total_sorties
 * @property float $total
 * @property float $total_entrees_nettes
 * @property float $total_entrees_reciproque
 * @property float $total_sorties_nettes
 * @property float $total_sorties_reciproque
 * @property float $total_debut_mois_interpro
 * @property float $total_entrees_interpro
 * @property float $total_sorties_interpro
 * @property float $total_interpro
 * @property acCouchdbJson $lieux

 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getSelecteur()
 * @method string setSelecteur()
 * @method float getTotalDebutMois()
 * @method float setTotalDebutMois()
 * @method float getTotalEntrees()
 * @method float setTotalEntrees()
 * @method float getTotalSorties()
 * @method float setTotalSorties()
 * @method float getTotal()
 * @method float setTotal()
 * @method float getTotalEntreesNettes()
 * @method float setTotalEntreesNettes()
 * @method float getTotalEntreesReciproque()
 * @method float setTotalEntreesReciproque()
 * @method float getTotalSortiesNettes()
 * @method float setTotalSortiesNettes()
 * @method float getTotalSortiesReciproque()
 * @method float setTotalSortiesReciproque()
 * @method float getTotalDebutMoisInterpro()
 * @method float setTotalDebutMoisInterpro()
 * @method float getTotalEntreesInterpro()
 * @method float setTotalEntreesInterpro()
 * @method float getTotalSortiesInterpro()
 * @method float setTotalSortiesInterpro()
 * @method float getTotalInterpro()
 * @method float setTotalInterpro()
 * @method acCouchdbJson getLieux()
 * @method acCouchdbJson setLieux()
 
 */

abstract class BaseDRMMention extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMMention';
    }
                
}