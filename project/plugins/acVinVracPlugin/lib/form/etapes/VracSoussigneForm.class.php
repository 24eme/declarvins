<?php
class VracSoussigneForm extends VracForm 
{
   	public function configure()
    {
		$this->setWidgets(array(
            'vendeur_type' => new sfWidgetFormChoice(array('choices' => $this->getVendeurTypes(), 'expanded' => true)),
            'vendeur_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => EtablissementFamilles::FAMILLE_PRODUCTEUR, 'only_actif' => 1)),
            'vendeur_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(), 'expanded' => true)),
            'acheteur_type' => new sfWidgetFormChoice(array('choices' => $this->getAcheteurTypes(), 'expanded'=> true)),
            'acheteur_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => EtablissementFamilles::FAMILLE_NEGOCIANT, 'only_actif' => 1)),
        	'acheteur_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
            'mandataire_exist' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'mandataire_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => EtablissementFamilles::FAMILLE_COURTIER, 'only_actif' => 1)),
        	'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'cas_particulier' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getCasParticulier()))
    	));
        $this->widgetSchema->setLabels(array(
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
        
        if ($type = $this->getObject()->vendeur_type) {
        	$this->setWidget('vendeur_identifiant', new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => $type, 'only_actif' => 1)));
        }
        
        if ($type = $this->getObject()->acheteur_type) {
        	$this->setWidget('acheteur_identifiant', new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => $type, 'only_actif' => 1)));
        }
        
        if ($this->getObject()->vous_etes == 'vendeur') {
        	$this->setWidget('vendeur_identifiant', new sfWidgetFormInputHidden());
        	$this->setValidator('vendeur_identifiant', new sfValidatorPass());
        }
        
        if ($this->getObject()->vous_etes == 'acheteur') {
        	$this->setWidget('acheteur_identifiant', new sfWidgetFormInputHidden());
        	$this->setValidator('acheteur_identifiant', new sfValidatorPass());
        }
        
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

      	if ($this->getEtablissement()) {
      		$this->setWidget('vous_etes_identifiant', new sfWidgetFormInputHidden(array('default' => $this->getEtablissement()->identifiant)));
      		$this->setValidator('vous_etes_identifiant', new ValidatorPass());
      	}
      
      if ($this->getObject()->hasVersion() && !$this->isAdmin()) {
      	$this->setWidget('vendeur_identifiant', new sfWidgetFormInputHidden());
      	$this->setWidget('acheteur_identifiant', new sfWidgetFormInputHidden());
      	$this->setWidget('mandataire_identifiant', new sfWidgetFormInputHidden());
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


      if (!$this->getObject()->mandataire_exist) {
      	$this->setDefault('mandataire_exist', 0);
      }
    }

    protected function doUpdateObject($values) {
    	if ($this->getObject()->hasVersion() && !$this->isAdmin()) {
    		return;
    	}

        if($this->etablissementIsCourtier()) {
          unset($values['mandataire_exist']);
          unset($values['mandataire_identifiant']);
          $this->getObject()->mandataire_identifiant = $this->getEtablissement()->identifiant;
          $this->getObject()->mandataire_exist = 1;
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


    public function isAdmin() {

        return sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR);
    }
}
