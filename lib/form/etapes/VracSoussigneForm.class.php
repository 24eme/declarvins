<?php
class VracSoussigneForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'vendeur_type',
           'vendeur_identifiant',
	       'vendeur_tva',
           'acheteur_type',
           'acheteur_identifiant',
	       'acheteur_tva',
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

      if ($this->getEtablissement()) {
        $this->setWidget('vous_etes', new sfWidgetFormChoice(array('choices' => $this->getVousEtes(), 'expanded' => true)));
        $this->setValidator('vous_etes', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getVousEtes()))));

        $this->getWidget('vous_etes')->setLabel("Vous êtes:");
      }
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();

      $this->setDefault('cas_particulier', (($this->getObject()->cas_particulier) ? $this->getObject()->cas_particulier : ConfigurationVrac::CAS_PARTICULIER_DEFAULT_KEY));
      $this->setDefault('premiere_mise_en_marche', true);

      if ($this->getEtablissement()) {
        if ($this->getEtablissement()->identifiant == $this->getObject()->acheteur_identifiant) {
          $this->setDefault('vous_etes', 'acheteur');
        }

        if ($this->getEtablissement()->identifiant == $this->getObject()->vendeur_identifiant) {
          $this->setDefault('vous_etes', 'vendeur');
        }
      }
    }

    protected function doUpdateObject($values) {
        if ($this->getEtablissement()) {
          $etablissement_type = $values['vous_etes'];
          unset($values[$etablissement_type]);
          unset($values[$etablissement_type."_type"]);
          unset($values[$etablissement_type."_identifiant"]);
          unset($values[$etablissement_type."_tva"]);
          $this->getObject()->set($etablissement_type."_type", $this->getEtablissement()->famille);
          $this->getObject()->set($etablissement_type."_identifiant", $this->getEtablissement()->identifiant);
        }

        parent::doUpdateObject($values);

        $this->getObject()->storeSoussignesInformations();
    }

    protected function getVousEtes() {

      return array('vendeur' => "Vendeur", 'acheteur' => "Acheteur");
    }
}
