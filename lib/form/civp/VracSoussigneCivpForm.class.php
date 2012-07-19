<?php
class VracSoussigneCivpForm extends VracForm 
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
	       'production_otna',
	       'apport_union',
	       'cession_interne',
	       'vendeur',
	       'acheteur',
	       'mandataire',
	       'adresse_stockage',
	       'adresse_livraison'
		));
		$this->widgetSchema->setNameFormat('vrac_soussigne[%s]');
    }
}