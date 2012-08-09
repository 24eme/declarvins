<?php
class VracSoussigneForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'vendeur_type',
           'vendeur_identifiant',
	       'vendeur_assujetti_tva',
           'acheteur_type',
           'acheteur_identifiant',
	       'acheteur_assujetti_tva',
	       'mandataire_exist',
	       'mandataire_identifiant',
	       'premiere_mise_en_marche',
	       'cas_particulier',
		   'vendeur',
		   'acheteur',
		   'mandataire',
		   'adresse_stockage',
		   'adresse_livraison',
		));
		$this->setDefaultValues();
    }

    public function setDefaultValues() 
    {
    	$this->getWidget('cas_particulier')->setDefault((($this->getObject()->cas_particulier)? $this->getObject()->cas_particulier : ConfigurationVrac::CAS_PARTICULIER_DEFAULT_KEY));
    	$this->getWidget('premiere_mise_en_marche')->setDefault(true);
    }
}
