<?php
/**
 * BaseDRMDetail
 * 
 * Base model for DRMDetail

 * @property string $code
 * @property string $libelle
 * @property string $selecteur
 * @property string $has_vrac
 * @property integer $pas_de_mouvement_check
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
 * @property string $interpro
 * @property acCouchdbJson $labels
 * @property acCouchdbJson $libelles_label
 * @property acCouchdbJson $cvo
 * @property acCouchdbJson $douane
 * @property string $label_supplementaire
 * @property acCouchdbJson $vrac
 * @property string $millesime
 * @property DRMDetailNoeud $stocks_debut
 * @property DRMDetailNoeud $entrees
 * @property DRMDetailNoeud $sorties
 * @property DRMDetailNoeud $stocks_fin

 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getSelecteur()
 * @method string setSelecteur()
 * @method string getHasVrac()
 * @method string setHasVrac()
 * @method integer getPasDeMouvementCheck()
 * @method integer setPasDeMouvementCheck()
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
 * @method string getInterpro()
 * @method string setInterpro()
 * @method acCouchdbJson getLabels()
 * @method acCouchdbJson setLabels()
 * @method acCouchdbJson getLibellesLabel()
 * @method acCouchdbJson setLibellesLabel()
 * @method acCouchdbJson getCvo()
 * @method acCouchdbJson setCvo()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
 * @method string getLabelSupplementaire()
 * @method string setLabelSupplementaire()
 * @method acCouchdbJson getVrac()
 * @method acCouchdbJson setVrac()
 * @method string getMillesime()
 * @method string setMillesime()
 * @method DRMDetailNoeud getStocksDebut()
 * @method DRMDetailNoeud setStocksDebut()
 * @method DRMDetailNoeud getEntrees()
 * @method DRMDetailNoeud setEntrees()
 * @method DRMDetailNoeud getSorties()
 * @method DRMDetailNoeud setSorties()
 * @method DRMDetailNoeud getStocksFin()
 * @method DRMDetailNoeud setStocksFin()
 
 */

abstract class BaseDRMDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetail';
    }
                
}