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
    
    protected static $_francize_date = array(
    	'date_debut_retiraison',
    	'date_limite_retiraison',
    	'date_stats',
    	'date_signature',
    );
    
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
    	$zones = array();
    	if (($etablissement = $this->getEtablissement()) && $this->getObject()->vendeur_type == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
            $zones = $etablissement->getConfigurationZones();
        }
        $date = $this->getObject()->valide->date_saisie;
    	$produits = $this->getConfiguration()->formatVracProduitsByZones($zones, $date);
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
    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        foreach (self::$_francize_date as $field) {
        	if (isset($defaults[$field]) && !empty($defaults[$field])) {
        		$defaults[$field] = Date::francizeDate($defaults[$field]);
        	}
        }       
        $this->setDefaults($defaults); 
    }
    
	public function getUpdatedObject() {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }

        try {
        	$this->updateObject();
        } catch (Exception $e) {
            throw $e;
        }

        return $this->getObject();
    }
}

