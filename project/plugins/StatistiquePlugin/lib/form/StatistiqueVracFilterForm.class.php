<?php
class StatistiqueVracFilterForm extends StatistiqueFilterForm
{
	const HASH_PRODUIT_DEFAUT = 'produit';
	const FORM_TEMPLATE = 'formVracStatistiqueFilter';
	
	protected $configurationVrac = null;
	
	protected static $fieldsConfig = array(
		'interpro' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'vendeur_identifiant' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'acheteur_identifiant' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'mandataire_identifiant' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'vendeur.code_postal' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'acheteur.code_postal' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'vendeur.famille' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'vendeur.sous_famille' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'acheteur.famille' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'acheteur.sous_famille' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'_id' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'valide.date_saisie' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'valide.date_validation' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'date_limite_retiraison' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'prix_unitaire' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'volume_propose' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'millesime' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'cas_particulier' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'labels' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'mentions' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'type_prix' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'type_transaction' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'annexe' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'export' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'produit' => StatistiqueQuery::CONFIG_QUERY_STRING_OR_PRODUCT_VRAC,
		'valide.statut' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
	);
	
	public function configure() 
	{
		parent::configure();
		$years = range(date('Y'), date('Y') - 20);
        $years = array_combine($years, $years);
		/**
		 * INTERPRO
		 */
        $this->setWidget('interpro', new sfWidgetFormInputHidden());
        $this->setValidator('interpro', new sfValidatorString(array('required' => false)));
        $this->getWidget('interpro')->setDefault($this->getInterproId());
		// ETABLISSEMENTS        
        $vendeur_etablissements = new StatistiqueEtablissementCollectionForm($this->getInterproId());
        $this->embedForm('vendeur_identifiant', $vendeur_etablissements);
        
        $acheteur_etablissements = new StatistiqueEtablissementCollectionForm($this->getInterproId());
        $this->embedForm('acheteur_identifiant', $acheteur_etablissements);
        
        $mandataire_etablissements = new StatistiqueEtablissementCollectionForm($this->getInterproId(), array('familles' => EtablissementFamilles::FAMILLE_COURTIER));
        $this->embedForm('mandataire_identifiant', $mandataire_etablissements);
        // DEPARTEMENTS
        $choices = $this->getDepartementsChoices();
        $this->setWidget('vendeur.code_postal', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('vendeur.code_postal', 'Vendeur code postal :');
        $this->setValidator('vendeur.code_postal', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
        $this->setWidget('acheteur.code_postal', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('acheteur.code_postal', 'Acheteur code postal :');
        $this->setValidator('acheteur.code_postal', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
        // FAMILLES
        $familleChoices = $this->getFamilles();
        $sousFamilleChoices = $this->getSousFamilles();
        $this->setWidget('vendeur.famille', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $familleChoices)));
        $this->setWidget('vendeur.sous_famille', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $sousFamilleChoices)));
        $this->widgetSchema->setLabel('vendeur.famille', 'Vendeur famille :');
        $this->widgetSchema->setLabel('vendeur.sous_famille', 'Vendeur sous famille :');
        $this->setValidator('vendeur.famille', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($familleChoices))));
        $this->setValidator('vendeur.sous_famille', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getSousFamilles()))));
        $this->setWidget('acheteur.famille', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $familleChoices)));
        $this->setWidget('acheteur.sous_famille', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $sousFamilleChoices)));
        $this->widgetSchema->setLabel('acheteur.famille', 'Acheteur famille :');
        $this->widgetSchema->setLabel('acheteur.sous_famille', 'Acheteur sous famille :');
        $this->setValidator('acheteur.famille', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($familleChoices))));
        $this->setValidator('acheteur.sous_famille', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($this->getSousFamilles()))));
		// IDENTIFIANT
        $this->setWidget('_id', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('_id', 'Identifiant :');
        $this->setValidator('_id', new sfValidatorString(array('required' => false)));
		// DATE SAISIE
        $this->setWidget('valide.date_saisie', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => $years)),
            'to_date'       => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => $years)),
            'template'      => '<br />du %from_date%<br />au %to_date%'
        )));
        $this->widgetSchema->setLabel('valide.date_saisie', 'Période de saisie :');
        $this->setValidator('valide.date_saisie', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date'   => new sfValidatorDate(array('required' => false)))));
		// DATE SIGNATURE
		$this->setWidget('valide.date_validation', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => $years)),
            'to_date'       => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => $years)),
            'template'      => '<br />du %from_date%<br />au %to_date%'
        )));
        $this->widgetSchema->setLabel('valide.date_validation', 'Période de signature :');
        $this->setValidator('valide.date_validation', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date'   => new sfValidatorDate(array('required' => false)))));
		
        // DATE RETIRAISON
        $this->setWidget('date_limite_retiraison', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => $years)),
            'to_date'       => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => $years)),
            'template'      => '<br />du %from_date%<br />au %to_date%'
        )));
        $this->widgetSchema->setLabel('date_limite_retiraison', 'Date limite de retiraison :');
        $this->setValidator('date_limite_retiraison', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date'   => new sfValidatorDate(array('required' => false)))));
		// RANGE PRIX
        $this->setWidget('prix_unitaire', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormInputFloat(),
            'to_date'       => new sfWidgetFormInputFloat(),
            'template'      => '<br />de %from_date%<br />à %to_date%'
        )));
        $this->widgetSchema->setLabel('prix_unitaire', 'Fourchette de prix :');
        $this->setValidator('prix_unitaire', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorNumber(array('required' => false)), 'to_date'   => new sfValidatorNumber(array('required' => false)))));
		// RANGE VOLUME
		$this->setWidget('volume_propose', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormInputFloat(),
            'to_date'       => new sfWidgetFormInputFloat(),
            'template'      => '<br />de %from_date%<br />à %to_date%'
        )));
        $this->widgetSchema->setLabel('volume_propose', 'Fourchette de volume :');
        $this->setValidator('volume_propose', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorNumber(array('required' => false)), 'to_date'   => new sfValidatorNumber(array('required' => false)))));
        // MILLESIME
        $this->setWidget('millesime', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('millesime', 'Millesime :');
        $this->setValidator('millesime', new sfValidatorString(array('required' => false)));
        // PRODUIT        
        $produits = new StatistiqueProduitCollectionForm($this->getInterproId());
        $this->embedForm('produit', $produits);
		// CONDITIONS
		$choices = $this->getCasParticulier();
        $this->setWidget('cas_particulier', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('cas_particulier', 'Conditions particulières :');
        $this->setValidator('cas_particulier', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
		// LABELS
		$choices = $this->getLabels();
        $this->setWidget('labels', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('labels', 'Label :');
        $this->setValidator('labels', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
		// MENTIONS
		$choices = $this->getMentions();
        $this->setWidget('mentions', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('mentions', 'Mention :');
        $this->setValidator('mentions', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
		// PRIX
		$choices = $this->getTypesPrix();
        $this->setWidget('type_prix', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('type_prix', 'Type de prix :');
        $this->setValidator('type_prix', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
		// TRANSACTION
		$choices = $this->getTypesTransaction();
        $this->setWidget('type_transaction', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('type_transaction', 'Type de transaction :');
        $this->setValidator('type_transaction', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));
		// ANNEXE
		$choices = $this->getChoixOuiNon();
        $this->setWidget('annexe', new sfWidgetFormChoice(array('expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('annexe', 'Présence annexe :');
        $this->setValidator('annexe', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
		// EXPORT
		$choices = $this->getChoixOuiNon();
        $this->setWidget('export', new sfWidgetFormChoice(array('expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('export', 'Conditionné export :');
        $this->setValidator('export', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // STATUT
		$choices = $this->getStatuts();
        $this->setWidget('valide.statut', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('valide.statut', 'Statut :');
        $this->setValidator('valide.statut', new sfValidatorChoice(array('multiple' => true, 'required' => false, 'choices' => array_keys($choices))));

        
  		$this->validatorSchema->setPostValidator(new StatistiqueVracFilterValidator());
        $this->widgetSchema->setNameFormat('statistique_vrac_filter[%s]');
        
    }
    
    public function getConfigurationVrac()
    {
    	if (!$this->configurationVrac) {
    		$this->configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($this->getInterproId());
    	}
    	return $this->configurationVrac;
    }

    
    public function getStatuts()
    {
    	$statuts = VracClient::getInstance()->getStatusContrat();
    	$result = array();
    	foreach ($statuts as $statut) {
    		$result[$statut] = $statut;
    	}
    	return $result;
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
    
    public function getProduits()
    {
    	$produits = array();
    	if ($values = $this->getValues()) {
    		if (isset($values['produit']) && !empty($values['produit'])) {
    			foreach ($values['produit'] as $p) {
    				if ($p['declaration']) {
    					$prod = str_replace('/declaration/', 'declaration/', $p['declaration']);
    					$produits[] = self::HASH_PRODUIT_DEFAUT.'.'.str_replace('/', '.', $prod);
    				}
    			}
    		}
    	}
    	if (count($produits) > 0) {
    		return $produits;
    	}
    	return null;
    }
    
    public function getDefaultQuery()
    {
    	$query_string = new acElasticaQueryQueryString('interpro:'.$this->getInterproId().' valide.statut:*');
    	return $query_string;
    }
    
    public function getQuery()
    {
    	$query = new acElasticaQueryMatchAll();
    	$statistiqueQuery = new StatistiqueQuery(self::$fieldsConfig, $this->getValues());
    	$filtered = new acElasticaFiltered($query, $statistiqueQuery->getFilter());
    	return $filtered;
    }
    
    public function getFormTemplate()
    {
    	return self::FORM_TEMPLATE;
    }
    
	public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
        	if (isset($taintedValues[$key])) {
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
    
	public function getEmbedFormTemplate($field, $form_item_class, $arg1 = null, $arg2 = null) {
        $vrac = new Vrac();
        $form_embed = new $form_item_class($arg1, $arg2);
        $form = new StatistiqueCollectionTemplateForm($this, $field, $form_embed);
        
        return $form->getFormTemplate();
    }

    public function getFormTemplateProduits() {

        return $this->getEmbedFormTemplate('declaration', 'StatistiqueProduitForm', $this->getInterproId());
    }

    public function getFormTemplateVendeurIdentifiant() {

        return $this->getEmbedFormTemplate('vendeur_identifiant', 'StatistiqueEtablissementForm', $this->getInterproId());
    }

    public function getFormTemplateAcheteurIdentifiant() {

        return $this->getEmbedFormTemplate('acheteur_identifiant', 'StatistiqueEtablissementForm', $this->getInterproId());
    }

    public function getFormTemplateMandataireIdentifiant() {

        return $this->getEmbedFormTemplate('mandataire_identifiant', 'StatistiqueEtablissementForm', $this->getInterproId(), array('familles' => EtablissementFamilles::FAMILLE_COURTIER));
    }
}