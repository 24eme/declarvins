<?php
class StatistiqueVracFilterForm extends StatistiqueFilterForm
{
	const HASH_PRODUIT_DEFAUT = 'produit';
	const FORM_TEMPLATE = 'formVracStatistiqueFilter';
	
	protected $configurationVrac = null;
	
	public function configure() 
	{
		parent::configure();
		/**
		 * INTERPRO
		 */
        $this->setWidget('interpro', new sfWidgetFormInputHidden());
        $this->setValidator('interpro', new sfValidatorString(array('required' => false)));
        $this->getWidget('interpro')->setDefault($this->getInterproId());
		// ETABLISSEMENTS
    	$options = array_merge(array('interpro_id' => $this->getInterproId()), $this->getOptions());
        $this->setWidget('vendeur_identifiant', new WidgetEtablissement($options));
        $this->widgetSchema->setLabel('vendeur_identifiant', 'Vendeur :');
        $this->setValidator('vendeur_identifiant', new ValidatorEtablissement(array('required' => false)));
        $this->setWidget('acheteur_identifiant', new WidgetEtablissement($options));
        $this->widgetSchema->setLabel('acheteur_identifiant', 'Vendeur :');
        $this->setValidator('acheteur_identifiant', new ValidatorEtablissement(array('required' => false)));
        $this->setWidget('mandataire_identifiant', new WidgetEtablissement($options));
        $this->widgetSchema->setLabel('mandataire_identifiant', 'Vendeur :');
        $this->setValidator('mandataire_identifiant', new ValidatorEtablissement(array('required' => false)));
        // DEPARTEMENTS
        $choices = array(''=>'')+$this->getDepartementsChoices();
        $this->setWidget('vendeur.code_postal', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('vendeur.code_postal', 'Vendeur code postal :');
        $this->setValidator('vendeur.code_postal', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        $this->setWidget('acheteur.code_postal', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('acheteur.code_postal', 'Acheteur code postal :');
        $this->setValidator('acheteur.code_postal', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // FAMILLES
        $familleChoices = array_merge(array(''=>''), $this->getFamilles());
        $sousFamilleChoices = array('' => '');
        $this->setWidget('vendeur.famille', new sfWidgetFormChoice(array('choices' => $familleChoices)));
        $this->setWidget('vendeur.sous_famille', new sfWidgetFormChoice(array('choices' => $sousFamilleChoices)));
        $this->widgetSchema->setLabel('vendeur.famille', 'Vendeur famille :');
        $this->widgetSchema->setLabel('vendeur.sous_famille', 'Vendeur sous famille :');
        $this->setValidator('vendeur.famille', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($familleChoices))));
        $this->setValidator('vendeur.sous_famille', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getSousFamilles()))));
        $this->setWidget('acheteur.famille', new sfWidgetFormChoice(array('choices' => $familleChoices)));
        $this->setWidget('acheteur.sous_famille', new sfWidgetFormChoice(array('choices' => $sousFamilleChoices)));
        $this->widgetSchema->setLabel('acheteur.famille', 'Acheteur famille :');
        $this->widgetSchema->setLabel('acheteur.sous_famille', 'Acheteur sous famille :');
        $this->setValidator('acheteur.famille', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($familleChoices))));
        $this->setValidator('acheteur.sous_famille', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getSousFamilles()))));
		// IDENTIFIANT
        $this->setWidget('_id', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('_id', 'Identifiant :');
        $this->setValidator('_id', new sfValidatorString(array('required' => false)));
		// DATE SAISIE
        $this->setWidget('valide.date_saisie', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('valide.date_saisie', 'Date de saisie :');
        $this->setValidator('valide.date_saisie', new sfValidatorString(array('required' => false)));
		// DATE RETIRAISON
        $this->setWidget('date_limite_retiraison', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('date_limite_retiraison', 'Date limite de retiraison :');
        $this->setValidator('date_limite_retiraison', new sfValidatorString(array('required' => false)));
		// MILLESIME
        $this->setWidget('millesime', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('millesime', 'Millesime :');
        $this->setValidator('millesime', new sfValidatorString(array('required' => false)));
        // PRODUIT
        $choices = array_merge(array(''=>''), $this->getProduits());
        $this->setWidget('produit', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('produit', 'Produit :');
        $this->setValidator('produit', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// CONDITIONS
		$choices = array_merge(array(''=>''), $this->getCasParticulier());
        $this->setWidget('cas_particulier', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('cas_particulier', 'Conditions particulières :');
        $this->setValidator('cas_particulier', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// LABELS
		$choices = array_merge(array(''=>''), $this->getLabels());
        $this->setWidget('labels', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('labels', 'Label :');
        $this->setValidator('labels', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// MENTIONS
		$choices = array_merge(array(''=>''), $this->getMentions());
        $this->setWidget('mentions', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('mentions', 'Mention :');
        $this->setValidator('mentions', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// PRIX
		$choices = array_merge(array(''=>''), $this->getTypesPrix());
        $this->setWidget('type_prix', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('type_prix', 'Type de prix :');
        $this->setValidator('type_prix', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// TRANSACTION
		$choices = array_merge(array(''=>''), $this->getTypesTransaction());
        $this->setWidget('type_transaction', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('type_transaction', 'Type de transaction :');
        $this->setValidator('type_transaction', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// ANNEXE
		$choices = array_merge(array(''=>''), $this->getChoixOuiNon());
        $this->setWidget('annexe', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('annexe', 'Présence annexe :');
        $this->setValidator('annexe', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// EXPORT
		$choices = array_merge(array(''=>''), $this->getChoixOuiNon());
        $this->setWidget('export', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('export', 'Conditionné export :');
        $this->setValidator('export', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));

        
        $this->widgetSchema->setNameFormat('statistique_vrac_filter[%s]');
        
    }
    
    public function getConfigurationVrac()
    {
    	if (!$this->configurationVrac) {
    		$this->configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($this->getInterproId());
    	}
    	return $this->configurationVrac;
    }

    
    public function getCasParticulier()
    {
    	return $this->getConfigurationVrac()->getCasParticulier()->toArray();
    }
    
    public function getLabels()
    {
    	return $this->getConfigurationVrac()->getLabels()->toArray();
    }
    
    public function getTypesPrix()
    {
    	return $this->getConfigurationVrac()->getTypesPrix()->toArray();
    }
    
    public function getMentions()
    {
    	return $this->getConfigurationVrac()->getMentions()->toArray();
    }
    
    public function getTypesTransaction()
    {
    	return $this->getConfigurationVrac()->getTypesTransaction()->toArray();
    }
    
    public function getChoixOuiNon()
    {
    	return array('1' => 'Oui', '0' =>'Non'); 
    }
    
    public function getProduit()
    {
    	$produit = self::HASH_PRODUIT_DEFAUT;
    	if ($values = $this->getValues()) {
    		if (isset($values['produit']) && !empty($values['produit'])) {
    			$produit .= '.'.str_replace('/', '.', $values['produit']);
    		}
    	}
    	return $produit;
    }
    

    public function getDefaultQuery()
    {
    	return 'interpro:'.$this->getInterproId();
    }
    
    public function getQuery()
    {
    	$query = '';
    	$values = $this->getValues();
    	foreach ($values as $node => $value) {
    		if ($value) {
    			if ($query) {
	    			$query .= ' ';
	    		}
		    	if ($node == 'produit') {
		    		$query .= $node.':declaration/'.$value.'*';
		    	} else {
    				$query .= $node.':'.$value;
		    	}
    		}
    	}
    	return $query;
    }
    
    public function getFormTemplate()
    {
    	return self::FORM_TEMPLATE;
    }
}