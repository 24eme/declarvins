<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EtablissementMoisStockForm extends acCouchdbForm {
    
    public $etablissement;
    
    public function __construct($etablissement, $options = array(), $CSRFSecret = null) {
        $this->etablissement = $etablissement;
        parent::__construct($etablissement, $this->getDefaultValues(), $options, $CSRFSecret);
    }

    public function getDefaultValues() {
        $defaults = array();
        if (!$this->etablissement->exist('mois_stock_debut') || !$this->etablissement->mois_stock_debut) {
            $defaults['mois_stock_debut'] = DRMPaiement::NUM_MOIS_DEBUT_CAMPAGNE;
        } else {
            $defaults['mois_stock_debut'] = $this->etablissement->mois_stock_debut;
        }
        return $defaults;
    }

    public function configure()
    {
        $this->setWidget('mois_stock_debut', new sfWidgetFormChoice(array('choices' => $this->getMonths())));
        $this->setValidator('mois_stock_debut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getMonths()))));
        
        $this->widgetSchema->setNameFormat('etablissement[%s]');
    }
    
    public function getMonths() 
    {
        $dateFormat = new sfDateFormat('fr_FR');
	    $results = array('' => '');
	    for ($i = 1; $i <= 12; $i++) { 
            $month = $dateFormat->format(date('Y').'-'.$i.'-01', 'MMMM');
            $results[$i] = $month;
	    }
	    return $results;
    }
    
    public function save() {
        $values = $this->getValues();
        if (!$this->etablissement->exist('mois_stock_debut')) {
            $this->etablissement->add('mois_stock_debut', $values['mois_stock_debut']);
        } else {
            $this->etablissement->mois_stock_debut = $values['mois_stock_debut'];
        }
        $this->etablissement->save();
    }
    
}

