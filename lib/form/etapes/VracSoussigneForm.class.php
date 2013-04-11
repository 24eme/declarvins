<?php
class VracSoussigneForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
		   'vous_etes',
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

      if (!$this->etablissementIsVendeurOrAcheteur()) {
        unset($this['vous_etes']);
      } else {
      	$this->setWidget('vous_etes_identifiant', new sfWidgetFormInputHidden(array('default' => $this->getEtablissement()->identifiant)));
      	$this->setValidator('vous_etes_identifiant', new sfValidatorPass());
      }

      if ($this->etablissementIsCourtier()) {
        unset($this['mandataire_exist']);
      }
  		$this->validatorSchema->setPostValidator(new VracSoussigneValidator());
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
        if ($this->etablissementIsVendeurOrAcheteur()) {
          $etablissement_type = $values['vous_etes'];
          unset($values[$etablissement_type]);
          unset($values[$etablissement_type."_type"]);
          unset($values[$etablissement_type."_identifiant"]);
          $this->getObject()->set($etablissement_type."_type", $this->getEtablissement()->famille);
          $this->getObject()->set($etablissement_type."_identifiant", $this->getEtablissement()->identifiant);
        }

        if($this->etablissementIsCourtier()) {
          unset($values['mandataire_exist']);
          unset($values['mandataire_identifiant']);
          $this->getObject()->mandataire_identifiant = $this->getEtablissement()->identifiant;
          $this->getObject()->mandataire_exist = 1;
          $this->getObject()->vous_etes = 'mandataire';
        }
		
      	$this->getObject()->cas_particulier_libelle = $this->getConfiguration()->formatCasParticulierLibelle(array($this->getObject()->cas_particulier));
        parent::doUpdateObject($values);

        $this->getObject()->storeSoussignesInformations();
    }

    public function etablissementIsVendeurOrAcheteur() {
      
      return $this->getEtablissement() && in_array($this->getEtablissement()->famille, array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT));
    }

    public function etablissementIsCourtier() {

      return $this->getEtablissement() && in_array($this->getEtablissement()->famille, array(EtablissementFamilles::FAMILLE_COURTIER));
    }
}
