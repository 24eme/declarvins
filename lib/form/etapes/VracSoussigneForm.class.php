<?php
class VracSoussigneForm extends VracForm 
{
   	public function configure()
    {
		$this->setWidgets(array(
    		'vous_etes' => new sfWidgetFormChoice(array('choices' => $this->getVousEtes(), 'expanded' => true)),
            'vendeur_type' => new sfWidgetFormChoice(array('choices' => $this->getVendeurTypes(), 'expanded' => true)),
            'vendeur_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'only_actif' => 1)),
            'vendeur_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(), 'expanded' => true)),
            'acheteur_type' => new sfWidgetFormChoice(array('choices' => $this->getAcheteurTypes(), 'expanded'=> true)),
            'acheteur_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'only_actif' => 1)),
        	'acheteur_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
            'mandataire_exist' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'mandataire_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => EtablissementFamilles::FAMILLE_COURTIER, 'only_actif' => 1)),
        	'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'cas_particulier' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getCasParticulier()))
    	));
        $this->widgetSchema->setLabels(array(
        	'vous_etes' => 'Vous êtes*: ',
        	'vendeur_type' => 'Type:',
        	'vendeur_identifiant' => 'Vendeur:',
        	'vendeur_tva' => 'Assujetti à la TVA',
        	'acheteur_type' => 'Type:',
        	'acheteur_identifiant' => 'Acheteur:',
        	'acheteur_tva' => 'Assujetti à la TVA',
        	'mandataire_exist' => 'Transaction avec un courtier',
        	'mandataire_identifiant' => 'Mandataire:',
        	'premiere_mise_en_marche' => 'Première mise en marché:',
        	'cas_particulier' => 'Condition particulière:'
        ));
        $this->setValidators(array(
            'vous_etes' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getVousEtes()))),
        	'vendeur_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getVendeurTypes()))),
        	'vendeur_identifiant' => new ValidatorEtablissement(array('required' => false)),
        	'vendeur_tva' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'acheteur_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAcheteurTypes()))),
        	'acheteur_identifiant' => new ValidatorEtablissement(array('required' => false)),
        	'acheteur_tva' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'mandataire_exist' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'mandataire_identifiant' => new ValidatorEtablissement(array('required' => false, 'familles' => EtablissementFamilles::FAMILLE_COURTIER)),
        	'premiere_mise_en_marche' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'cas_particulier' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCasParticulier())))
        ));
        
        $vracVendeurFormName = $this->vracVendeurFormName();
    	$vendeur = new $vracVendeurFormName($this->getObject()->vendeur);
        $this->embedForm('vendeur', $vendeur);
        $vracAcheteurFormName = $this->vracAcheteurFormName();
        $acheteur = new $vracAcheteurFormName($this->getObject()->acheteur);
        $this->embedForm('acheteur', $acheteur);
        $vracMandataireFormName = $this->vracMandataireFormName();
        $mandataire = new VracMandataireForm($this->getObject()->mandataire);
        $this->embedForm('mandataire', $mandataire);
        
        $vracStockageFormName = $this->vracStockageFormName();
        $stockage = new $vracStockageFormName($this->getObject()->adresse_stockage);
        $this->embedForm('adresse_stockage', $stockage);
        $vracLivraisonFormName = $this->vracLivraisonFormName();
        $livraison = new $vracLivraisonFormName($this->getObject()->adresse_livraison);
        $this->embedForm('adresse_livraison', $livraison);

      if (!$this->etablissementIsVendeurOrAcheteur()) {
        unset($this['vous_etes']);
      } else {
      	$this->setWidget('vous_etes_identifiant', new sfWidgetFormInputHidden(array('default' => $this->getEtablissement()->identifiant)));
      	$this->setValidator('vous_etes_identifiant', new ValidatorPass());
      }

      if ($this->etablissementIsCourtier()) {
        unset($this['mandataire_exist']);
      }
      $this->widgetSchema->setNameFormat('vrac_soussigne[%s]');
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
        
        if (!$this->getObject()->mandataire_exist) {
        	$this->getObject()->remove('mandataire');
        	$this->getObject()->add('mandataire');
        }

        $this->getObject()->storeSoussignesInformations();
    }

    public function etablissementIsVendeurOrAcheteur() {
      
      return $this->getEtablissement() && in_array($this->getEtablissement()->famille, array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT));
    }

    public function etablissementIsCourtier() {

      return $this->getEtablissement() && in_array($this->getEtablissement()->famille, array(EtablissementFamilles::FAMILLE_COURTIER));
    }
}
