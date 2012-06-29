<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneModificationForm
 * @author mathurin
 */
class VracSoussigneModificationForm extends acCouchdbObjectForm {
    
   private $type = null;
   
    public function configure()
    {
        $this->type = $this->getObject()->famille;
        
        if($this->type == "Viticulteur") $this->configureAcheteurVendeur('vendeur');
        if($this->type == "Negociant") $this->configureAcheteurVendeur('acheteur');
        if($this->type == "Courtier") $this->configureMandataire();
        
        $this->setDefault('adresse', $this->getObject()->siege->adresse);
        $this->setDefault('code_postal', $this->getObject()->siege->code_postal);
        $this->setDefault('commune', $this->getObject()->siege->commune);
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->siege->adresse = $values['adresse'];
        $this->getObject()->siege->code_postal = $values['code_postal'];
        $this->getObject()->siege->commune = $values['commune'];
    }
    
    private function configureAcheteurVendeur($label)
    {    
        $this->setWidget('cvi', new sfWidgetFormInput());        
        $this->setWidget('no_accises', new sfWidgetFormInput());        
        $this->setWidget('adresse', new sfWidgetFormInput());        
        $this->setWidget('code_postal', new sfWidgetFormInput());   
        $this->setWidget('commune', new sfWidgetFormInput());
        $this->setWidget('no_tva_intracommunautaire', new sfWidgetFormInput());
        
        
        $this->widgetSchema->setLabels(array(
            'cvi' => 'N° CVI',
            'no_accises' => 'N° ACCISE',
            'adresse' => 'Adresse*',
            'code_postal' => 'CP',
            'commune' => 'Ville*',
            'no_tva_intracommunautaire' => 'TVA Intracomm.'
        ));
                
        $this->setValidators(array(
            'cvi' => new sfValidatorNumber(array('required' => false)),
            'no_accises' => new sfValidatorString(array('required' => false)),
            'adresse' => new sfValidatorString(array('required' => true, 'min_length' => 5)),
            'code_postal' => new sfValidatorString(array('required' => false, 'min_length' => 5,'max_length' => 5)),
            'commune' => new sfValidatorString(array('required' => true, 'min_length' => 2)),
            'no_tva_intracommunautaire' => new sfValidatorString(array('required' => false))
            ));
        
        $this->widgetSchema->setNameFormat('vrac[%s]');    
    }
    
    private function configureMandataire() {
                
        $this->setWidget('carte_pro', new sfWidgetFormInput());          
        $this->setWidget('adresse', new sfWidgetFormInput());        
        $this->setWidget('code_postal', new sfWidgetFormInput());
        $this->setWidget('commune', new sfWidgetFormInput());   
        
        $this->widgetSchema->setLabels(array(
            'carte_pro' => 'N° carte professionnelle',
            'adresse' => 'Adresse*',
            'code_postal' => 'CP',
            'commune' => 'Ville'
        ));
        
       $this->setValidators(
       array(
            'carte_pro' => new sfValidatorNumber(array('required' => false)),
            'adresse' => new sfValidatorString(array('required' => true, 'min_length' => 5)),
            'code_postal' => new sfValidatorString(array('required' => false, 'min_length' => 5,'max_length' => 5)),
            'commune' => new sfValidatorString(array('required' => false, 'min_length' => 2))
            ));
       $this->widgetSchema->setNameFormat('vrac[%s]');        
    }


}

?>
