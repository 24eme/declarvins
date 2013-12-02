<?php
class CompteModificationForm extends acVinCompteModificationForm {
    
    /**
     * 
     */
    public function configure() {
    	parent::configure();
    	$this->setWidget('nom', new sfWidgetFormInputText());
    	$this->setWidget('prenom', new sfWidgetFormInputText());
    	$this->setWidget('telephone', new sfWidgetFormInputText());
    	$this->setWidget('fax', new sfWidgetFormInputText());
    	
    	$this->getWidget('nom')->setLabel('Nom*: ');
    	$this->getWidget('prenom')->setLabel('Prénom: ');
    	$this->getWidget('telephone')->setLabel('Téléphone: ');
    	$this->getWidget('fax')->setLabel('Fax: ');
    	
    	$this->setValidator('nom', new sfValidatorString(array('required' => true)));
    	$this->setValidator('prenom', new sfValidatorString(array('required' => false)));
    	$this->setValidator('telephone', new sfValidatorString(array('required' => false)));
    	$this->setValidator('fax', new sfValidatorString(array('required' => false)));
    }
    
}