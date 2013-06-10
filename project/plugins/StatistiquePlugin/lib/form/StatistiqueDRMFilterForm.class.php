<?php
class StatistiqueDRMFilterForm extends StatistiqueFilterForm
{
	const HASH_PRODUIT_DEFAUT = 'declaration';
	
	public function configure() 
	{
		parent::configure();
		/**
		 * DECLARANT
		 */
		// ETABLISSEMENT
    	$options = array_merge(array('interpro_id' => $this->getInterproId()), $this->getOptions());
        $this->setWidget('identifiant', new WidgetEtablissement($options));
        $this->widgetSchema->setLabel('identifiant', 'Etablissement :');
        $this->setValidator('identifiant', new ValidatorEtablissement(array('required' => false)));
        // DEPARTEMENTS
        $choices = array(''=>'')+$this->getDepartementsChoices();
        $this->setWidget('declarant.siege.code_postal', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('declarant.siege.code_postal', 'Code postal :');
        $this->setValidator('declarant.siege.code_postal', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // DOUANES
        $choices = array_merge(array(''=>''), $this->getDouanes());
        $this->setWidget('declarant.service_douane', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('declarant.service_douane', 'Service douane :');
        $this->setValidator('declarant.service_douane', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // FAMILLES
        $familleChoices = array_merge(array(''=>''), $this->getFamilles());
        $sousFamilleChoices = array('' => '');
        $this->setWidget('declarant.famille', new sfWidgetFormChoice(array('choices' => $familleChoices)));
        $this->setWidget('declarant.sous_famille', new sfWidgetFormChoice(array('choices' => $sousFamilleChoices)));
        $this->widgetSchema->setLabel('declarant.famille', 'Famille :');
        $this->widgetSchema->setLabel('declarant.sous_famille', 'Sous famille :');
        $this->setValidator('declarant.famille', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($familleChoices))));
        $this->setValidator('declarant.sous_famille', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getSousFamilles()))));
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
        $this->setWidget('valide.date_saisie', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('valide.date_saisie', 'Date de saisie :');
        $this->setValidator('valide.date_saisie', new sfValidatorString(array('required' => false)));
		// DATE SAISIE
        $this->setWidget('valide.date_signee', new sfWidgetFormInputText());
        $this->widgetSchema->setLabel('valide.date_signee', 'Date de signature :');
        $this->setValidator('valide.date_signee', new sfValidatorString(array('required' => false)));
		// MODE DE SAISIE
		$choices = array_merge(array(''=>''), $this->getModesDeSaisie());
        $this->setWidget('mode_de_saisie', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('mode_de_saisie', 'Mode de saisie :');
        $this->setValidator('mode_de_saisie', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // CAMPAGNE
        $choices = array_merge(array(''=>''), $this->getCampagneChoices());
        $this->setWidget('campagne', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('campagne', 'Campagne :');
        $this->setValidator('campagne', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        // PERIODE
        $years = range(date('Y'), date('Y') - 20);
        $years = array_combine($years, $years);
        $this->setWidget('periode', new WidgetFormPeriode(array('format' => '%year%-%month%', 'years' => $years)));
        $this->widgetSchema->setLabel('periode', 'Période :');
        $this->setValidator('periode', new ValidatorPeriode(array('required' => false, 'date_output' => 'Y-m')));
		/**
		 * PRODUIT
		 */
        // PRODUIT
        $choices = array_merge(array(''=>''), $this->getProduits());
        $this->setWidget('declaration', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->widgetSchema->setLabel('declaration', 'Produit :');
        $this->setValidator('declaration', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices))));
        
        $this->widgetSchema->setNameFormat('statistique_drm_filter[%s]');
        
    }
    
    protected function getDouanes() 
    {
        $douanes = DouaneAllView::getInstance()->findActives()->rows;
        $choices = array();
        foreach ($douanes as $douane) {
        	$value = $douane->value;
            $choices[$value[DouaneAllView::VALUE_DOUANE_NOM]] = $value[DouaneAllView::VALUE_DOUANE_NOM];
        }
        return $choices;
    }
    protected function getFamilles() 
    {
        $familles = EtablissementFamilles::getFamilles();
        $choices = array();
        foreach ($familles as $key => $famille) {
            $choices[$key] = $famille;
        }
        return $choices;
    }
    
	protected function getSousFamilles() 
	{
    	$sousFamilles =  EtablissementFamilles::getSousFamilles();	
    	$result = array();
    	foreach ($sousFamilles as $sousFamillesByFamille) {
    		foreach ($sousFamillesByFamille as $sousFamille => $value) {
    			$result[$sousFamille] = $sousFamille;
    		}
    	}
    	return $result;
    }
    
    protected function getModesDeSaisie()
    {
    	return DRMClient::getInstance()->getModesDeSaisie();
    }

    
    protected function getCampagneChoices()
    {
    	$years = range(date('Y'), date('Y') - 20);
    	$choices = array();
    	foreach ($years as $year) {
    		$campagne = ($year-1).'-'.$year;
    		$choices[$campagne] = $campagne;
    	}
    	return $choices;
    }
    
    protected function getProduits()
    {
    	return ConfigurationClient::getInstance()->findTreeProduitsLibelleForAdmin($this->getInterproId(), false);
    }
    
    public function getProduit()
    {
    	$produit = self::HASH_PRODUIT_DEFAUT;
    	if ($values = $this->getValues()) {
    		if (isset($values['declaration']) && !empty($values['declaration'])) {
    			$produit .= '.'.str_replace('/', '.', $values['declaration']);
    		}
    	}
    	return $produit;
    }
    
    public function getQuery()
    {
    	$query = '';
    	$values = $this->getValues();
    	foreach ($values as $node => $value) {
    		if ($value && $node != 'declaration') {
    			if ($query) {
	    			$query .= ' ';
	    		}
    			$query .= $node.':'.$value;
    		}
    	}
    	if ($values['declaration']) {
    		if ($query) {
    			$query .= ' ';
    		}
    		$query .= self::HASH_PRODUIT_DEFAUT.'.'.str_replace('/', '.', $values['declaration']).'.selecteur:1';
    	}
    	return $query;
    }
}