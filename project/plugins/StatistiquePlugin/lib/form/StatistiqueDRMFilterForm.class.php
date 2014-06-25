<?php
class StatistiqueDRMFilterForm extends StatistiqueFilterForm
{
	const HASH_PRODUIT_DEFAUT = 'declaration';
	const FORM_TEMPLATE = 'formDRMStatistiqueFilter';
	const TERM_BOOST = 1.0;
	
	protected static $fieldsConfig = array(
		'interpros' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'identifiant' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'declarant.siege.code_postal' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'declarant.service_douane' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'declarant.famille' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'declarant.sous_famille' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'_id' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'identifiant_drm_historique' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'valide.date_saisie' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'valide.date_signee' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'mode_de_saisie' => StatistiqueQuery::CONFIG_QUERY_STRING_OR,
		'campagne' => StatistiqueQuery::CONFIG_QUERY_STRING,
		'periode' => StatistiqueQuery::CONFIG_QUERY_RANGE,
		'declaration' => StatistiqueQuery::CONFIG_QUERY_STRING_OR_PRODUCT
	);
	
	
	public function configure() 
	{
		parent::configure();
		$years = range(date('Y'), date('Y') - 20);
        $years = array_combine($years, $years);
		/**
		 * INTERPRO
		 */
        $this->setWidget('interpros', new sfWidgetFormInputHidden());
        $this->setValidator('interpros', new sfValidatorString(array('required' => false)));
        $this->getWidget('interpros')->setDefault($this->getInterproId());
        $this->setWidget('referente', new sfWidgetFormInputHidden());
        $this->setValidator('referente', new sfValidatorString(array('required' => false)));
        $this->getWidget('referente')->setDefault(1);
		/**
		 * DECLARANT
		 */
		// ETABLISSEMENT
        $etablissements = new StatistiqueEtablissementCollectionForm($this->getInterproId());
        $this->embedForm('identifiant', $etablissements);
        // DEPARTEMENTS
        $choices = $this->getDepartementsChoices();
        $this->setWidget('declarant.siege.code_postal', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('declarant.siege.code_postal', 'Code postal :');
        $this->setValidator('declarant.siege.code_postal', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($choices))));
        // DOUANES
        $choices = $this->getDouanes();
        $this->setWidget('declarant.service_douane', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('declarant.service_douane', 'Service douane :');
        $this->setValidator('declarant.service_douane', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($choices))));
        // FAMILLES
        $familleChoices = $this->getFamilles();
        $sousFamilleChoices = $this->getSousFamilles();
        $this->setWidget('declarant.famille', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $familleChoices)));
        $this->setWidget('declarant.sous_famille', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $sousFamilleChoices)));
        $this->widgetSchema->setLabel('declarant.famille', 'Famille :');
        $this->widgetSchema->setLabel('declarant.sous_famille', 'Sous famille :');
        $this->setValidator('declarant.famille', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($familleChoices))));
        $this->setValidator('declarant.sous_famille', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($this->getSousFamilles()))));
        /**
         * DRM
         */
		// IDENTIFIANT
        $this->setWidget('_id', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('_id', 'Identifiant :');
        $this->setValidator('_id', new sfValidatorString(array('required' => false)));
		// IDENTIFIANT HISTORIQUE
        $this->setWidget('identifiant_drm_historique', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('identifiant_drm_historique', 'Identifiant historique :');
        $this->setValidator('identifiant_drm_historique', new sfValidatorString(array('required' => false)));
		// DATE SAISIE
        $this->setWidget('valide.date_saisie', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormInputText(array(), array('class' => 'datepicker')),
            'to_date'       => new sfWidgetFormInputText(array(), array('class' => 'datepicker')),
            'template'      => '<br />du %from_date%<br />au %to_date%'
        )));
        $this->widgetSchema->setLabel('valide.date_saisie', 'Période de saisie :');
        $this->setValidator('valide.date_saisie', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)), 'to_date'   => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)))));
		// DATE DE SIGNATURE
		$this->setWidget('valide.date_signee', new sfWidgetFormDateRange(array(
            'from_date'     => new sfWidgetFormInputText(array(), array('class' => 'datepicker')),
            'to_date'       => new sfWidgetFormInputText(array(), array('class' => 'datepicker')),
            'template'      => '<br />du %from_date%<br />au %to_date%'
        )));
        $this->widgetSchema->setLabel('valide.date_signee', 'Période de signature :');
        $this->setValidator('valide.date_signee', new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)), 'to_date'   => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)))));
		
        // MODE DE SAISIE
		$choices = $this->getModesDeSaisie();
        $this->setWidget('mode_de_saisie', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => true, 'choices' => $choices)));
        $this->widgetSchema->setLabel('mode_de_saisie', 'Mode de saisie :');
        $this->setValidator('mode_de_saisie', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($choices))));
        // CAMPAGNE
        $choices = array_merge(array(''=>''), $this->getCampagneChoices());
        $this->setWidget('campagne', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('campagne', 'Campagne :');
        $this->setValidator('campagne', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // PERIODE
        $this->setWidget('periode', new sfWidgetFormDateRange(array(
            'from_date'     => new WidgetFormPeriode(array('format' => '%year% - %month%', 'years' => $years)),
            'to_date'       => new WidgetFormPeriode(array('format' => '%year% - %month%', 'years' => $years)),
            'template'      => '<br />du %from_date%<br />au %to_date%'
        )));
        $this->widgetSchema->setLabel('periode', 'Période :');
        $this->setValidator('periode', new sfValidatorDateRange(array('required' => false, 'from_date' => new ValidatorPeriode(array('required' => false, 'date_output' => 'Y-m')), 'to_date'   => new ValidatorPeriode(array('required' => false, 'date_output' => 'Y-m')))));
		/**
		 * PRODUIT
		 */
        // PRODUIT        
        $produits = new StatistiqueProduitCollectionForm($this->getInterproId());
        $this->embedForm('declaration', $produits);
        
        $this->widgetSchema->setNameFormat('statistique_drm_filter[%s]');
        
    }
    
    public function getFiltres($values = null)
    {
    	$filtres = array();
    	if ($values = $this->getValues()) {
    		if ($values['identifiant']) {
    			foreach ($values['identifiant'] as $identifiant) {
    				if ($identifiant['identifiant']) {
    					$filtres[] = 'filtre_etablissements';
    					break;
    				}
    			}
    		}
    		if ($values['declarant.siege.code_postal']) {
    			$filtres[] = 'filtre_code_postal';
    		}
    		if ($values['declarant.service_douane']) {
    			$filtres[] = 'filtre_service_douane';
    		}
    		if ($values['declarant.famille']) {
    			$filtres[] = 'filtre_famille';
    		}
    		if ($values['declarant.sous_famille']) {
    			$filtres[] = 'filtre_sous_famille';
    		}
    		if ($values['_id']) {
    			$filtres[] = 'filtre_identifiant';
    		}
    		if ($values['identifiant_drm_historique']) {
    			$filtres[] = 'filtre_identifiant_historique';
    		}
    		if ($values['mode_de_saisie']) {
    			$filtres[] = 'filtre_mode_saisie';
    		}
    		if ($values['campagne']) {
    			$filtres[] = 'filtre_campagne';
    		}
    		if ($values['valide.date_saisie']['from'] || $values['valide.date_saisie']['to']) {
    			$filtres[] = 'filtre_date_saisie';
    		}
    		if ($values['valide.date_signee']['from'] || $values['valide.date_signee']['to']) {
    			$filtres[] = 'filtre_date_signee';
    		}
    		if ($values['periode']['from'] || $values['periode']['to']) {
    			$filtres[] = 'filtre_periode';
    		}
    		if ($values['declaration']) {
    			$produits = array();
    			foreach ($values['declaration'] as $identifiant) {
    				if ($identifiant['declaration']) {
    					$filtres[] = 'filtre_produits';
    					break;
    				}
    			}
    		}
    	}
    	return $filtres;
    }
    
    public function getProduits()
    {
    	$produits = array();
    	if ($values = $this->getValues()) {
    		if (isset($values['declaration']) && !empty($values['declaration'])) {
    			foreach ($values['declaration'] as $p) {
    				if ($p['declaration']) {
    					$prod = str_replace('/declaration/', '', $p['declaration']);
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
    	$query_string = new acElasticaQueryQueryString('interpros:'.$this->getInterproId().' referente:1');
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
    
	public function getEmbedFormTemplate($field, $form_item_class, $arg1 = null) {
        $vrac = new Vrac();
        $form_embed = new $form_item_class($arg1);
        $form = new StatistiqueCollectionTemplateForm($this, $field, $form_embed);
        
        return $form->getFormTemplate();
    }

    public function getFormTemplateProduits() {

        return $this->getEmbedFormTemplate('declaration', 'StatistiqueProduitForm', $this->getInterproId());
    }

    public function getFormTemplateEtablissements() {

        return $this->getEmbedFormTemplate('identifiant', 'StatistiqueEtablissementForm', $this->getInterproId());
    }
}