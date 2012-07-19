<?php
class VracSoussigneIrForm extends VracForm 
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
		   'adresse_livraison',
		));
		$this->getWidget('vendeur_type')->setOption('expanded', true);
		$this->getWidget('acheteur_type')->setOption('expanded', true);
		$this->getWidget('vendeur_assujetti_tva')->setOption('expanded', true);
		$this->getWidget('acheteur_assujetti_tva')->setOption('expanded', true);
		$this->setWidget('mandataire_exist', new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)));
		$this->setValidator('mandataire_exist', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))));
		$this->widgetSchema->setLabel('mandataire_exist', 'Transaction avec un courtier:');
		$this->widgetSchema->setNameFormat('vrac_soussigne[%s]');
    }
}