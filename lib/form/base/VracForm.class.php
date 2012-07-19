<?php
class VracForm extends acCouchdbObjectForm 
{
	protected $configuration;
    
	public function __construct(ConfigurationVrac $configuration, acCouchdbJson $object, $options = array(), $CSRFSecret = null) 
	{
        $this->setConfiguration($configuration);
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function getConfiguration()
    {
    	return $this->configuration;
    }
    
    public function setConfiguration($configuration)
    {
    	$this->configuration = $configuration;
    }
    
	public function configure()
    {
        $this->setWidgets(array(
        	'numero_contrat' => new sfWidgetFormInputText(),
        	'etape' => new sfWidgetFormInputText(),
        	'vendeur_type' => new sfWidgetFormChoice(array('choices' => $this->getVendeurTypes())),
        	'vendeur_identifiant' => new sfWidgetFormChoice(array('choices' => $this->getVendeurs())),
        	'vendeur_assujetti_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'acheteur_type' => new sfWidgetFormChoice(array('choices' => $this->getAcheteurTypes())),
        	'acheteur_identifiant' => new sfWidgetFormChoice(array('choices' => $this->getAcheteurs())),
        	'acheteur_assujetti_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'mandatants' => new sfWidgetFormChoice(array('choices' => $this->getMandatants(), 'multiple' => true)),
        	'mandataire_exist' => new sfWidgetFormInputCheckbox(),
        	'mandataire_identifiant' => new sfWidgetFormChoice(array('choices' => $this->getMandataires())),
        	'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'production_otna' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'apport_union' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'cession_interne' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'original' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'type_transaction' => new sfWidgetFormChoice(array('choices' => $this->getTypesTransaction())),
        	'produit' => new sfWidgetFormChoice(array('choices' => $this->getProduits())),
        	'type_domaine' => new sfWidgetFormChoice(array('choices' => $this->getTypesDomaine())),
        	'domaine' => new sfWidgetFormInputText(),
        	'labels' => new sfWidgetFormChoice(array('choices' => $this->getLabels(), 'multiple' => true)),
        	'mentions' => new sfWidgetFormChoice(array('choices' => $this->getMentions(), 'multiple' => true)),
        	'volume_propose' => new sfWidgetFormInputFloat(),
        	'annexe' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'prix_unitaire' => new sfWidgetFormInputFloat(),
        	'type_prix' => new sfWidgetFormChoice(array('choices' => $this->getTypesPrix())),
        	'determination_prix' => new sfWidgetFormInputText(),
        	'date_limite_retiraison' => new sfWidgetFormInputText(),
        	'commentaires_conditions' => new sfWidgetFormTextarea(),
        	'part_cvo' => new sfWidgetFormInputText(),
        	'prix_total' => new sfWidgetFormInputFloat(),
        	'conditions_paiement' => new sfWidgetFormChoice(array('choices' => $this->getConditionsPaiement(), 'multiple' => true)),
        	'type_echeancier_paiement' => new sfWidgetFormInputText(),
        	'vin_livre' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'date_debut_retiraison' => new sfWidgetFormInputText(),
        	'calendrier_retiraison' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'contrat_pluriannuel' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'reference_contrat_pluriannuel' => new sfWidgetFormInputText(),
        	'delai_paiement' => new sfWidgetFormChoice(array('choices' => $this->getDelaisPaiement())),
        	'echeancier_paiement' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'clause_reserve_retiraison' => new sfWidgetFormInputCheckbox(),
        	'export' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'type_contrat' => new sfWidgetFormChoice(array('choices' => $this->getTypesContrat())),
        	'prix_variable' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'part_variable' => new sfWidgetFormInputText(),
        	'cvo_nature' => new sfWidgetFormChoice(array('choices' => $this->getCvoNatures())),
        	'cvo_repartition' => new sfWidgetFormChoice(array('choices' => $this->getCvoRepartitions())),
        	'date_stats' => new sfWidgetFormInputText(),
        	'date_signature' => new sfWidgetFormInputText(),
        	'volume_enleve' => new sfWidgetFormInputFloat(),
        	'nature_document' => new sfWidgetFormChoice(array('choices' => $this->getNaturesDocument())),
        	'date_signature_vendeur' => new sfWidgetFormInputText(),
        	'date_signature_acheteur' => new sfWidgetFormInputText(),
        	'date_signature_mandataire' => new sfWidgetFormInputText(),
        	'commentaires' => new sfWidgetFormTextarea()
        ));
        $this->widgetSchema->setLabels(array(
            'numero_contrat' => 'Numéro du contrat: ',
        	'etape' => 'Etape:',
        	'vendeur_type' => 'Type:',
        	'vendeur_identifiant' => 'Vendeur:',
        	'vendeur_assujetti_tva' => 'Assujetti à la TVA',
        	'acheteur_type' => 'Type:',
        	'acheteur_identifiant' => 'Acheteur:',
        	'acheteur_assujetti_tva' => 'Assujetti à la TVA',
        	'mandatants' => 'Mandatants:',
        	'mandataire_exist' => 'Présence d\'un mandataire?',
        	'mandataire_identifiant' => 'Mandataire:',
        	'premiere_mise_en_marche' => 'Première mise en marché:',
        	'production_otna' => 'Contrat entre producteurs 5% ou OTNA:',
        	'apport_union' => 'Apport contractuel à une union:',
        	'cession_interne' => 'Contrat interne entre deux filiales:',
        	'original' => 'En attente de l\'original:',
        	'type_transaction' => 'Type de transaction:',
        	'produit' => 'Produit:',
        	'type_domaine' => 'Type de domaine:',
        	'domaine' => 'Domaine:',
        	'labels' => 'Labels:',
        	'mentions' => 'Mentions:',
        	'volume_propose' => 'Volume proposé:',
        	'annexe' => 'Présence d\'une annexe:',
        	'prix_unitaire' => 'Prix:',
        	'type_prix' => 'Type de prix:',
        	'determination_prix' => 'Mode de détermination du prix définitif:',
        	'date_limite_retiraison' => 'Date limite de retiraison:',
        	'commentaires_conditions' => 'Commentaires:',
        	'part_cvo' => 'Part CVO payé par l\'acheteur:',
        	'prix_total' => 'Prix total:',
        	'conditions_paiement' => 'Conditions générales de paiement:',
        	'type_echeancier_paiement' => 'Echéancier de paiement:',
        	'vin_livre' => 'Le vin sera livré:',
        	'date_debut_retiraison' => 'Date de début de retiraison:',
        	'calendrier_retiraison' => 'Calendrier de retiraison:',
        	'contrat_pluriannuel' => 'Contrat pluriannuel écrit:',
        	'reference_contrat_pluriannuel' => 'Référence contrat pluriannuel:',
        	'delai_paiement' => 'Delai de paiement:',
        	'echeancier_paiement' => 'Echeancier de paiement:',
        	'clause_reserve_retiraison' => 'Clause de reserve de propriété',
        	'export' => 'Expédition export:',
        	'type_contrat' => 'Type de contrat:',
        	'prix_variable' => 'Partie de prix variable:',
        	'part_variable' => 'Part du prix variable sur la quantité:',
        	'cvo_nature' => 'Nature de la transaction:',
        	'cvo_repartition' => 'Répartition de la CVO:',
        	'date_stats' => 'Date de statistique:',
        	'date_signature' => 'Date de signature:',
        	'volume_enleve' => 'Volume enlevé:',
        	'nature_document' => 'Nature:',
        	'date_signature_vendeur' => 'Date de signature:',
        	'date_signature_acheteur' => 'Date de signature:',
        	'date_signature_mandataire' => 'Date de signature:',
        	'commentaires' => 'Commentaires:'
        ));
        $this->setValidators(array(
            'numero_contrat' => new sfValidatorString(array('required' => false)),
        	'etape' => new sfValidatorString(array('required' => false)),
        	'vendeur_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getVendeurTypes()))),
        	'vendeur_identifiant' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getVendeurs()))),
        	'vendeur_assujetti_tva' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'acheteur_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAcheteurTypes()))),
        	'acheteur_identifiant' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAcheteurs()))),
        	'acheteur_assujetti_tva' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'mandatants' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMandatants()), 'multiple' => true)),
        	'mandataire_exist' => new sfValidatorPass(),
        	'mandataire_identifiant' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMandataires()))),
        	'premiere_mise_en_marche' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'production_otna' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'apport_union' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'cession_interne' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'original' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'type_transaction' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesTransaction()))),
        	'produit' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getProduits()))),
        	'type_domaine' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesDomaine()))),
        	'domaine' => new sfValidatorString(array('required' => false)),
        	'labels' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getLabels()), 'multiple' => true)),
        	'mentions' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMentions()), 'multiple' => true)),
        	'volume_propose' => new sfValidatorNumber(array('required' => false)),
        	'annexe' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'prix_unitaire' => new sfValidatorNumber(array('required' => false)),
        	'type_prix' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesPrix()))),
        	'determination_prix' => new sfValidatorString(array('required' => false)),
        	'date_limite_retiraison' => new sfValidatorString(array('required' => false)),
        	'commentaires_conditions' => new sfValidatorString(array('required' => false)),
        	'part_cvo' => new sfValidatorString(array('required' => false)),
        	'prix_total' => new sfValidatorNumber(array('required' => false)),
        	'conditions_paiement' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getConditionsPaiement()), 'multiple' => true)),
        	'type_echeancier_paiement' => new sfValidatorString(array('required' => false)),
        	'vin_livre' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'date_debut_retiraison' => new sfValidatorString(array('required' => false)),
        	'calendrier_retiraison' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'contrat_pluriannuel' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'reference_contrat_pluriannuel' => new sfValidatorString(array('required' => false)),
        	'delai_paiement' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getDelaisPaiement()))),
        	'echeancier_paiement' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'clause_reserve_retiraison' => new sfValidatorPass(),
        	'export' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'type_contrat' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesContrat()))),
        	'prix_variable' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'part_variable' => new sfValidatorString(array('required' => false)),
        	'cvo_nature' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCvoNatures()))),
        	'cvo_repartition' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCvoRepartitions()))),
        	'date_stats' => new sfValidatorString(array('required' => false)),
        	'date_signature' => new sfValidatorString(array('required' => false)),
        	'volume_enleve' => new sfValidatorNumber(array('required' => false)),
        	'nature_document' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getNaturesDocument()))),
        	'date_signature_vendeur' => new sfValidatorString(array('required' => false)),
        	'date_signature_acheteur' => new sfValidatorString(array('required' => false)),
        	'date_signature_mandataire' => new sfValidatorString(array('required' => false)),
        	'commentaires' => new sfValidatorString(array('required' => false))
                ));
         
    	$vendeur = new VracVendeurForm($this->getObject()->vendeur);
        $this->embedForm('vendeur', $vendeur);
        $acheteur = new VracAcheteurForm($this->getObject()->acheteur);
        $this->embedForm('acheteur', $acheteur);
        $mandataire = new VracMandataireForm($this->getObject()->mandataire);
        $this->embedForm('mandataire', $mandataire);
        
        
        $stockage = new VracStockageForm($this->getObject()->adresse_stockage);
        $this->embedForm('adresse_stockage', $stockage);
        $livraison = new VracLivraisonForm($this->getObject()->adresse_livraison);
        $this->embedForm('adresse_livraison', $livraison);
        
        
        $valide = new VracValideForm($this->getObject()->valide);
        $this->embedForm('valide', $valide);
        
        $paiements = new VracPaiementCollectionForm($this->getObject()->paiements);
        $this->embedForm('paiements', $paiements);
        
        $retiraisons = new VracRetiraisonCollectionForm($this->getObject()->retiraisons);
        $this->embedForm('retiraisons', $retiraisons);
        
        $lots = new VracLotCollectionForm($this->getObject()->lots);
        $this->embedForm('lots', $lots);
        
        $this->widgetSchema->setNameFormat('vrac[%s]');
        
    }
    
    public function getChoixOuiNon()
    {
    	return array('1' => 'Oui', '0' =>'Non'); 
    }
    
    public function getVendeurTypes()
    {
    	return $this->getConfiguration()->getVendeurTypes()->toArray();
    }
    
    public function getAcheteurTypes()
    {
    	return $this->getConfiguration()->getAcheteurTypes()->toArray();
    }
    
    public function getVendeurs()
    {
    	return $this->formatEtablissements($this->getConfiguration()->getVendeurs());
    }
    
    public function getAcheteurs()
    {
    	return $this->formatEtablissements($this->getConfiguration()->getAcheteurs());
    }
    
    public function getMandatants()
    {
    	return $this->getConfiguration()->getMandatants();
    }
    
    public function getMandataires()
    {
    	return $this->formatEtablissements($this->getConfiguration()->getMandataires());
    }
    public function getTypesTransaction()
    {
    	return $this->getConfiguration()->getTypesTransaction()->toArray();
    }
    
    public function getProduits()
    {
    	$produits = $this->getConfiguration()->getConfig()->formatProduits();
    	$produits[''] = '';
    	ksort($produits);
    	return $produits;
    }
    
    public function getLabels()
    {
    	return $this->getConfiguration()->getLabels()->toArray();
    }
    
    public function getMentions()
    {
    	return $this->getConfiguration()->getMentions()->toArray();
    }
    
    public function getTypesPrix()
    {
    	return $this->getConfiguration()->getTypesPrix()->toArray();
    }
    
    public function getConditionsPaiement()
    {
    	return $this->getConfiguration()->getConditionsPaiement()->toArray();
    }
    
    public function getTypesContrat()
    {
    	return $this->getConfiguration()->getTypesContrat()->toArray();
    }
    
    public function getCvoNatures()
    {
    	return $this->getConfiguration()->getCvoNatures()->toArray();
    }
    
    public function getCvoRepartitions()
    {
    	return $this->getConfiguration()->getCvoRepartitions()->toArray();
    }
    
    public function getNaturesDocument()
    {
    	return $this->getConfiguration()->getNaturesDocument()->toArray();
    }
    
    public function getTypesDomaine()
    {
    	return $this->getConfiguration()->getTypesDomaine()->toArray();
    }
    
    public function getDelaisPaiement()
    {
    	return $this->getConfiguration()->getDelaisPaiement()->toArray();
    }

    protected function formatEtablissements($datas) 
    {
    	$etablissements = array('' => '');
    	foreach($datas as $data) {
            $labels = array($data->key[EtablissementAllView::KEY_IDENTIFIANT], $data->key[EtablissementAllView::KEY_NOM], $data->key[EtablissementAllView::KEY_FAMILLE]);
            $etablissements[$data->id] = implode(', ', array_filter($labels));
        }
        return $etablissements;
    }
    
	public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
            if($form instanceof FormBindableInterface) {
                $form->bind($taintedValues[$key], $taintedFiles[$key]);
            }
        }       
        parent::bind($taintedValues, $taintedFiles);
    }
    
}