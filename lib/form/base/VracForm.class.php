<?php
class VracForm extends acCouchdbObjectForm 
{
	protected $configuration;
    protected $etablissement;
    protected $user;
    
    const VRAC_VENDEUR_FORM = 'VracVendeurForm';
    const VRAC_ACHETEUR_FORM = 'VracAcheteurForm';
    const VRAC_MANDATAIRE_FORM = 'VracMandataireForm';
    const VRAC_STOCKAGE_FORM = 'VracStockageForm';
    const VRAC_LIVRAISON_FORM = 'VracLivraisonForm';
    const VRAC_VALIDE_FORM = 'VracValideForm';
    const VRAC_PAIEMENT_FORM = 'VracPaiementForm';
    const VRAC_LOT_FORM = 'VracLotForm';
    
	public function __construct(ConfigurationVrac $configuration, $etablissement, $user, acCouchdbJson $object, $options = array(), $CSRFSecret = null) 
	{
        $this->setConfiguration($configuration);
        $this->setEtablissement($etablissement);
        $this->setUser($user);
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

    public function getEtablissement()
    {
    	return $this->etablissement;
    }
    
    public function setEtablissement($etablissement)
    {
    	$this->etablissement = $etablissement;
    }

    public function getUser()
    {
    	return $this->user;
    }
    
    public function setUser($user)
    {
    	$this->user = $user;
    }
    
	public function configure()
    {

    	$this->setWidgets(array(
    		'vous_etes' => new sfWidgetFormChoice(array('choices' => $this->getVousEtes(), 'expanded' => true)),
        	'numero_contrat' => new sfWidgetFormInputText(),
        	'etape' => new sfWidgetFormInputText(),
            'vendeur_type' => new sfWidgetFormChoice(array('choices' => $this->getVendeurTypes(), 'expanded' => true)),
            'vendeur_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'))),
            'vendeur_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(), 'expanded' => true)),
            'acheteur_type' => new sfWidgetFormChoice(array('choices' => $this->getAcheteurTypes(), 'expanded'=> true)),
            'acheteur_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'))),
        	'acheteur_tva' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'mandatants' => new sfWidgetFormChoice(array('choices' => $this->getMandatants(), 'multiple' => true)),
            'mandataire_exist' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'mandataire_identifiant' => new WidgetEtablissement(array('interpro_id' => $this->getInterpro()->get('_id'), 'familles' => EtablissementFamilles::FAMILLE_COURTIER)),
        	'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'cas_particulier' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getCasParticulier())),
        	'original' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'has_transaction' => new sfWidgetFormInputCheckbox(),
        	'has_cotisation_cvo' => new sfWidgetFormInputHidden(array('default' => 1)),
        	'type_transaction' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesTransaction())),
        	'produit' => new sfWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')),
        	'millesime' => new sfWidgetFormInputText(),
        	'type_domaine' => new sfWidgetFormChoice(array('choices' => $this->getTypesDomaine())),
        	'domaine' => new sfWidgetFormInputText(),
        	'labels' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getLabels())),
        	'mentions' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getMentions(), 'multiple' => true)),
        	'volume_propose' => new sfWidgetFormInputFloat(),
        	'annexe' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'prix_unitaire' => new sfWidgetFormInputFloat(),
        	'type_prix' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesPrix())),
        	'determination_prix' => new sfWidgetFormTextarea(),
        	'date_debut_retiraison' => new sfWidgetFormInputText(),
        	'date_limite_retiraison' => new sfWidgetFormInputText(),
        	'commentaires_conditions' => new sfWidgetFormTextarea(),
        	'prix_total' => new sfWidgetFormInputHidden(),
	        'part_cvo' => new sfWidgetFormInputHidden(),
	        'repartition_cvo_acheteur' => new sfWidgetFormInputHidden(array('default' => ConfigurationVrac::REPARTITION_CVO_ACHETEUR)),
        	'conditions_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getConditionsPaiement(), 'multiple' => false)),
        	'type_echeancier_paiement' => new sfWidgetFormInputText(),
        	'vin_livre' => new sfWidgetFormChoice(array('choices' => $this->getChoixVinLivre(),'expanded' => true)),
        	'contrat_pluriannuel' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'reference_contrat_pluriannuel' => new sfWidgetFormInputText(),
        	'delai_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getDelaisPaiement())),
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
        	'vous_etes' => 'Vous êtes: ',
            'numero_contrat' => 'Numéro du contrat: ',
        	'etape' => 'Etape:',
        	'vendeur_type' => 'Type:',
        	'vendeur_identifiant' => 'Vendeur:',
        	'vendeur_tva' => 'Assujetti à la TVA',
        	'acheteur_type' => 'Type:',
        	'acheteur_identifiant' => 'Acheteur:',
        	'acheteur_tva' => 'Assujetti à la TVA',
        	'mandatants' => 'Mandatants:',
        	'mandataire_exist' => 'Transaction avec un courtier',
        	'mandataire_identifiant' => 'Mandataire:',
        	'premiere_mise_en_marche' => 'Première mise en marché:',
        	'cas_particulier' => 'Condition particulère:',
        	'original' => 'En attente de l\'original:',
        	'has_transaction' => 'Je souhaite associer une déclaration de transaction',
        	'has_cvo' => 'Cvo',
        	'type_transaction' => 'Type de transaction:',
        	'produit' => 'Produit:',
        	'millesime' => 'Millesime:',
        	'type_domaine' => 'Type de domaine:',
        	'domaine' => 'Domaine:',
        	'labels' => 'Labels:',
        	'mentions' => 'Mentions:',
        	'volume_propose' => 'Volume total proposé:',
        	'annexe' => 'Présence d\'une annexe (cahier des charges techniques):',
        	'prix_unitaire' => 'Prix unitaire net HT hors cotisation:',
        	'type_prix' => 'Type de prix:',
        	'determination_prix' => 'Mode de détermination du prix définitif:',
        	'date_debut_retiraison' => 'Date de début de retiraison:',
        	'date_limite_retiraison' => 'Date limite de retiraison:',
        	'commentaires_conditions' => 'Commentaires:',
        	'prix_total' => 'Prix total HT:',
        	'part_cvo' => 'Part CVO:',
        	'repartition_cvo_acheteur' => 'Repartition CVO acheteur:',
        	'conditions_paiement' => 'Conditions générales de vente:',
        	'type_echeancier_paiement' => 'Echéancier de paiement:',
        	'vin_livre' => 'Le vin sera:',
        	'contrat_pluriannuel' => 'Contrat pluriannuel écrit:',
        	'reference_contrat_pluriannuel' => 'Référence contrat pluriannuel:',
        	'delai_paiement' => 'Delai de paiement:',
        	'echeancier_paiement' => 'Echeancier de paiement:',
        	'clause_reserve_retiraison' => 'Clause de reserve de propriété (si réserve, recours possible jusqu\'au paiement complet): ',
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
            'vous_etes' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getVousEtes()))),
            'numero_contrat' => new sfValidatorString(array('required' => false)),
        	'etape' => new sfValidatorString(array('required' => false)),
        	'vendeur_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getVendeurTypes()))),
        	'vendeur_identifiant' => new ValidatorEtablissement(array('required' => false)),
        	'vendeur_tva' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'acheteur_type' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getAcheteurTypes()))),
        	'acheteur_identifiant' => new ValidatorEtablissement(array('required' => false)),
        	'acheteur_tva' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'mandatants' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMandatants()), 'multiple' => true)),
        	'mandataire_exist' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'mandataire_identifiant' => new ValidatorEtablissement(array('required' => false, 'familles' => EtablissementFamilles::FAMILLE_COURTIER)),
        	'premiere_mise_en_marche' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'cas_particulier' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCasParticulier()))),
        	'original' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'has_transaction' => new sfValidatorPass(),
        	'has_cotisation_cvo' => new sfValidatorPass(),
        	'type_transaction' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesTransaction()))),
        	'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))),
        	'millesime' => new sfValidatorString(array('required' => false)),
        	'type_domaine' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesDomaine()))),
        	'domaine' => new sfValidatorString(array('required' => false)),
        	'labels' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getLabels()))),
        	'mentions' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMentions()), 'multiple' => true)),
        	'volume_propose' => new sfValidatorNumber(array('required' => true)),
        	'annexe' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'prix_unitaire' => new sfValidatorNumber(array('required' => true)),
        	'type_prix' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesPrix()))),
        	'determination_prix' => new sfValidatorString(array('required' => false)),
        	'date_debut_retiraison' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'date_limite_retiraison' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'commentaires_conditions' => new sfValidatorString(array('required' => false)),
        	'prix_total' => new sfValidatorNumber(array('required' => false)),
	        'part_cvo' => new sfValidatorPass(),
	        'repartition_cvo_acheteur' => new sfValidatorPass(),
        	'conditions_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getConditionsPaiement()), 'multiple' => false)),
        	'type_echeancier_paiement' => new sfValidatorString(array('required' => false)),
        	'vin_livre' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixVinLivre()))),
        	'contrat_pluriannuel' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'reference_contrat_pluriannuel' => new sfValidatorString(array('required' => false)),
        	'delai_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDelaisPaiement()))),
        	'echeancier_paiement' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'clause_reserve_retiraison' => new sfValidatorPass(),
        	'export' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'type_contrat' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesContrat()))),
        	'prix_variable' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'part_variable' => new sfValidatorString(array('required' => false)),
        	'cvo_nature' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCvoNatures()))),
        	'cvo_repartition' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCvoRepartitions()))),
        	'date_stats' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'date_signature' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'volume_enleve' => new sfValidatorNumber(array('required' => false)),
        	'nature_document' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getNaturesDocument()))),
        	'date_signature_vendeur' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'date_signature_acheteur' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'date_signature_mandataire' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'commentaires' => new sfValidatorString(array('required' => false))
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
        
        $vracValideFormName = $this->vracValideFormName();
        $valide = new VracValideForm($this->getObject()->valide);
        $this->embedForm('valide', $valide);
        
        $paiements = new VracPaiementCollectionForm($this->vracPaiementFormName(), $this->getObject()->paiements);
        $this->embedForm('paiements', $paiements);
        
        $lots = new VracLotCollectionForm($this->vracLotFormName(), $this->getConfiguration(), $this->getObject()->lots);
        $this->embedForm('lots', $lots);
        
        $this->widgetSchema->setNameFormat('vrac[%s]');
        
    }
    
    public function getChoixOuiNon()
    {
    	return array('1' => 'Oui', '0' =>'Non'); 
    }

    public function getChoixVinLivre()
    {
        return VracClient::getInstance()->getStatutsVin(); 
    }
    
    public function getVendeurTypes()
    {
    	return $this->getConfiguration()->getVendeurTypes()->toArray();
    }
    
    public function getAcheteurTypes()
    {
    	return $this->getConfiguration()->getAcheteurTypes()->toArray();
    }
    
    public function getMandatants()
    {
    	return $this->getConfiguration()->getMandatants();
    }
    
    public function getTypesTransaction()
    {
    	return $this->getConfiguration()->getTypesTransaction()->toArray();
    }
    
    public function getCasParticulier()
    {
    	return $this->getConfiguration()->getCasParticulier()->toArray();
    }
    
    public function getProduits()
    {
    	$produits = $this->getConfiguration()->formatVracProduitsByInterpro();
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


    protected function doUpdateObject($values) {
        
        $this->getObject()->fromArray($values);
    }
    
	public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
            if($form instanceof FormBindableInterface) {
                $form->bind($taintedValues[$key], $taintedFiles[$key]);
                $this->updateEmbedForm($key, $form);
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function updateEmbedForm($name, $form) {
    	$this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public function getFormTemplate($field, $form_item_class) {
        $vrac = new Vrac();
        $form_embed = new $form_item_class($vrac->get($field)->add());
        $form = new VracCollectionTemplateForm($this, $field, $form_embed);
        
        return $form->getFormTemplate();
    }

    public function getFormTemplatePaiements() {

        return $this->getFormTemplate('paiements', 'VracPaiementForm');
    }

    public function getFormTemplateLots() {
        $vrac = new Vrac();
        $form_dynamique = $this->vracLotFormName();
        $form_embed = new $form_dynamique($this->getConfiguration(), $vrac->lots->add());
        $form = new VracCollectionTemplateForm($this, 'lots', $form_embed);

        return $form->getFormTemplate();
    }

    public function getFormTemplateLotMillesimes($key) {
        $vrac = new Vrac();
        $form_embed = new VracLotMillesimeForm($this->getConfiguration(), $vrac->lots->add($key)->millesimes->add());
        $form = new VracCollectionTemplateForm($this, 'lots]['.$key.'][millesimes', $form_embed, 'var---nbItem---');

        return $form->getFormTemplate();
    }

    public function getFormTemplateLotCuves($key) {
        $vrac = new Vrac();
        $form_embed = new VracLotCuveForm($this->getConfiguration(), $vrac->lots->add($key)->cuves->add());
        $form = new VracCollectionTemplateForm($this, 'lots]['.$key.'][cuves', $form_embed, 'var---nbItem---');

        return $form->getFormTemplate();
    }
    
    public function vracVendeurFormName() { return self::VRAC_VENDEUR_FORM; }
	public function vracAcheteurFormName() { return self::VRAC_ACHETEUR_FORM; }
	public function vracMandataireFormName() { return self::VRAC_MANDATAIRE_FORM; }
	public function vracStockageFormName() { return self::VRAC_STOCKAGE_FORM; }
	public function vracLivraisonFormName() { return self::VRAC_LIVRAISON_FORM; }
	public function vracValideFormName() { return self::VRAC_VALIDE_FORM; }
	public function vracPaiementFormName() { return self::VRAC_PAIEMENT_FORM; }
	public function vracLotFormName() { return self::VRAC_LOT_FORM; }
	
	public function getInterpro() 
	{
        if ($this->getEtablissement()) {
        	
            return $this->getEtablissement()->getInterproObject();
        } else {
            
            return $this->getUser()->getCompte()->getGerantInterpro();
        }
	}

    protected function getVousEtes() {

      return array('vendeur' => "Vendeur", 'acheteur' => "Acheteur");
    }

    protected function getUser() {

        return sfContext::getInstance()->getUser();
    }
}

