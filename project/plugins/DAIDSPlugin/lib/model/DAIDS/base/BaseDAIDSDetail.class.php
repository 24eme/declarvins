<?php
/**
 * BaseDAIDSDetail
 * 
 * Base model for DAIDSDetail

 * @property string $code
 * @property string $libelle
 * @property acCouchdbJson $labels
 * @property acCouchdbJson $libelles_label
 * @property string $label_supplementaire
 * @property string $millesime
 * @property acCouchdbJson $douane
 * @property float $total_manquants_excedents
 * @property float $total_pertes_autorisees
 * @property float $total_manquants_taxables
 * @property float $total_douane
 * @property float $total_cvo
 * @property float $stock_theorique
 * @property DAIDSDetailStocks $stocks
 * @property float $stock_chais
 * @property float $stock_propriete
 * @property DAIDSDetailStockProprieteDetails $stock_propriete_details
 * @property float $stock_mensuel_theorique
 * @property DAIDSStocksMoyen $stocks_moyen

 * @method string getCode()
 * @method string setCode()
 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getLabels()
 * @method acCouchdbJson setLabels()
 * @method acCouchdbJson getLibellesLabel()
 * @method acCouchdbJson setLibellesLabel()
 * @method string getLabelSupplementaire()
 * @method string setLabelSupplementaire()
 * @method string getMillesime()
 * @method string setMillesime()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
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
 * @method float getStockTheorique()
 * @method float setStockTheorique()
 * @method DAIDSDetailStocks getStocks()
 * @method DAIDSDetailStocks setStocks()
 * @method float getStockChais()
 * @method float setStockChais()
 * @method float getStockPropriete()
 * @method float setStockPropriete()
 * @method DAIDSDetailStockProprieteDetails getStockProprieteDetails()
 * @method DAIDSDetailStockProprieteDetails setStockProprieteDetails()
 * @method float getStockMensuelTheorique()
 * @method float setStockMensuelTheorique()
 * @method DAIDSStocksMoyen getStocksMoyen()
 * @method DAIDSStocksMoyen setStocksMoyen()
 
 */

abstract class BaseDAIDSDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSDetail';
    }
                
}