<?php
class VracMarcheForm extends VracForm 
{
   	public function configure()
    {   		
    		$this->setWidgets(array(
        	'has_cotisation_cvo' => new sfWidgetFormInputHidden(array('default' => 1)),
        	'type_transaction' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesTransaction())),
        	'labels_arr' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true, 'choices' => $this->getLabels())),
        	'mentions' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getMentions(), 'multiple' => true)),
        	'volume_propose' => new sfWidgetFormInputFloat(),
        	'poids' => new sfWidgetFormInputFloat(),
        	'prix_unitaire' => new sfWidgetFormInputFloat(),
        	'type_prix' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesPrix())),
        	'determination_prix' => new sfWidgetFormTextarea(),
        	'determination_prix_date' => new sfWidgetFormInputText(),
        	'prix_total' => new sfWidgetFormInputHidden(),
	        'part_cvo' => new sfWidgetFormInputHidden(),
	        'repartition_cvo_acheteur' => new sfWidgetFormInputHidden(array('default' => ConfigurationVrac::REPARTITION_CVO_ACHETEUR)),
    		'conditions_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getConditionsPaiement(), 'multiple' => false)),
    		'delai_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getDelaisPaiement())),
    	));
        $this->widgetSchema->setLabels(array(
        	'has_cotisation_cvo' => 'Cvo',
        	'type_transaction' => 'Type de produit:',
        	'labels_arr' => 'Labels:',
        	'mentions' => 'Mentions:',
        	'volume_propose' => 'Volume total proposé*:',
        	'poids' => 'Poids*:',
        	'prix_unitaire' => 'Prix unitaire net HT hors cotisation*:',
        	'type_prix' => 'Type de prix*:',
        	'determination_prix' => 'Mode de détermination du prix définitif*:',
        	'determination_prix_date' => 'Date de détermination du prix définitif*:',
        	'prix_total' => 'Prix total HT:',
        	'part_cvo' => 'Part CVO:',
        	'repartition_cvo_acheteur' => 'Repartition CVO acheteur:',
        	'conditions_paiement' => 'Conditions générales de vente*:',
        	'delai_paiement' => 'Delai de paiement*:',
        ));
        $min = ($this->getObject()->volume_enleve)? $this->getObject()->volume_enleve : 0;
        $minErreur = ($min > 1)? $min.' hl ont déjà été enlevés pour ce contrat' : $min.' hl a déjà été enlevé pour ce contrat';
        $this->setValidators(array(
        	'has_cotisation_cvo' => new ValidatorPass(),
        	'type_transaction' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesTransaction()))),
        	'labels_arr' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getLabels()), 'multiple' => true)),
        	'mentions' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMentions()), 'multiple' => true)),
        	'volume_propose' => new sfValidatorNumber(array('required' => true, 'min' => $min), array('min' => $minErreur)),
        	'poids' => new sfValidatorNumber(array('required' => false)),
        	'prix_unitaire' => new sfValidatorNumber(array('required' => true)),
        	'type_prix' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesPrix()))),
        	'determination_prix' => new sfValidatorString(array('required' => false)),
        	'determination_prix_date' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'prix_total' => new sfValidatorNumber(array('required' => false)),
	        'part_cvo' => new sfValidatorNumber(array('required' => false)),
	        'repartition_cvo_acheteur' => new sfValidatorNumber(array('required' => false)),
            'conditions_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getConditionsPaiement()), 'multiple' => false)),
            'delai_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDelaisPaiement()))),
         ));
        
        $paiements = new VracPaiementCollectionForm($this->vracPaiementFormName(), $this->getObject()->paiements);
        $this->embedForm('paiements', $paiements);
        
        $cepages = $this->getCepages();
    	if (count($cepages) > 0) {
    		$cepages = array_merge(array('' => ''), $this->getCepages());
    		$this->setWidget('cepages', new sfWidgetFormChoice(array('choices' => $cepages), array('class' => 'autocomplete')));
    		$this->widgetSchema->setLabel('cepages', 'Cépage:');
    		$this->setValidator('cepages', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($cepages))));
    	}
    		
  		    $this->validatorSchema->setPostValidator(new VracMarcheValidator());
    		$this->widgetSchema->setNameFormat('vrac_marche[%s]');

        if (count($this->getTypesTransaction()) < 2) {
            unset($this['type_transaction']);
        }
    }
    protected function doUpdateObject($values) {
        if ($values['conditions_paiement'] != VracClient::ECHEANCIER_PAIEMENT) {
            $values['paiements'] = array();
            $this->getObject()->remove('paiements');
            $this->getObject()->add('paiements');
        }
        $this->getObject()->conditions_paiement_libelle = $this->getConfiguration()->formatConditionsPaiementLibelle(array($this->getObject()->conditions_paiement));
        parent::doUpdateObject($values);
        
        if (isset($values['cepages']) && $values['cepages']) {
        	$this->getObject()->produit = $values['cepages'];
        	$configuration = ConfigurationClient::getCurrent();
        	$configurationProduit = $configuration->getConfigurationProduit($this->getObject()->produit);
        	$this->getObject()->setDetailProduit($configurationProduit);
        	$this->getObject()->produit_libelle = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles());
        } else {
        	$configuration = ConfigurationClient::getCurrent();
        	$configurationProduit = $configuration->getConfigurationProduit($this->getObject()->produit);
        	$this->getObject()->produit = $configurationProduit->getCouleur()->getHash().'/cepages/'.ConfigurationProduit::DEFAULT_KEY;
        	$this->getObject()->setDetailProduit($configurationProduit);
        	$this->getObject()->produit_libelle = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles());
        	
        }

        $types_transaction = $this->getTypesTransaction();
        if (count($types_transaction) == 1) {
            foreach($types_transaction as $key => $value) {
                $this->getObject()->type_transaction = $key;
            }
        }

        if (!in_array($this->getObject()->type_prix, $this->getTypePrixNeedDetermination())) {
          $this->getObject()->determination_prix = null;
          $this->getObject()->determination_prix_date = null;
        }
        $this->getObject()->labels_libelle = $this->getConfiguration()->formatLabelsLibelle(array($this->getObject()->labels));
        $this->getObject()->mentions_libelle = $this->getConfiguration()->formatMentionsLibelle($this->getObject()->mentions);
        $this->getObject()->type_transaction_libelle = $this->getConfiguration()->formatTypesTransactionLibelle(array($this->getObject()->type_transaction));
        $this->getObject()->update();
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      
      $this->setDefault('cepages', $this->getObject()->produit);
      
      if (is_null($this->getObject()->type_transaction)) {
        $this->setDefault('type_transaction', VracClient::TRANSACTION_DEFAUT);
      }      
      if (is_null($this->getObject()->type_prix)) {
        $this->setDefault('type_prix', VracClient::PRIX_DEFAUT);
      }  
      if (!(count($this->getObject()->labels_arr->toArray()) > 0)) {
        $this->setDefault('labels_arr', '');
      }  
      if (!(count($this->getObject()->mentions->toArray()) > 0)) {
        $this->setDefault('mentions', '');
      }  
      if ($this->getObject()->determination_prix_date) {
      	$d = new DateTime($this->getObject()->determination_prix_date);
        $this->setDefault('determination_prix_date', $d->format('d/m/Y'));
      }
    }
    
    public function getCepages()
    {
    	return $this->getObject()->getCepagesProduit();
    }

    public function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }


    public function getCgpEcheancierNeedDetermination() {
      return 'echeancier_paiement';
    }

    public function getCgpContratNeedDetermination() {
      return 'contrat_pluriannuel';
    }

    public function getCgpDelaiNeedDetermination() {
      return 'cadre_reglementaire';
    }
}