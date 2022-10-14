<?php
class VracMarcheForm extends VracForm
{
   	public function configure()
    {
   	    $typePrix1 = array('definitif' => 'Définitif', 'non_definitif' => 'Prix non définitif');
   	    $typePrix2 = array('objectif' => 'D\'objectif', 'acompte' => 'D\'acompte');

    		$this->setWidgets(array(
        	'has_cotisation_cvo' => new sfWidgetFormInputHidden(array('default' => 1)),
        	'volume_propose' => new sfWidgetFormInputFloat(),
        	'prix_unitaire' => new sfWidgetFormInputFloat(),
        	'type_prix_1' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $typePrix1)),
    		'type_prix_2' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $typePrix2)),
        	'determination_prix' => new sfWidgetFormTextarea(),
        	'determination_prix_date' => new sfWidgetFormInputText(),
        	'prix_total' => new sfWidgetFormInputHidden(),
	        'part_cvo' => new sfWidgetFormInputHidden(),
	        'repartition_cvo_acheteur' => new sfWidgetFormInputHidden(array('default' => ConfigurationVrac::REPARTITION_CVO_ACHETEUR)),
    		'conditions_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getConditionsPaiement(), 'multiple' => false)),
    		'delai_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getDelaisPaiement())),
    		'clause_reserve_retiraison' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'date_debut_retiraison' => new sfWidgetFormInputText(),
        	'date_limite_retiraison' => new sfWidgetFormInputText(),
        	'vin_livre' => new sfWidgetFormChoice(array('choices' => $this->getChoixVinLivre(),'expanded' => true)),
        	'type_retiraison' => new sfWidgetFormChoice(array('choices' => $this->getChoixTypeRetiraison(),'expanded' => true)),
    	));
        $this->widgetSchema->setLabels(array(
        	'has_cotisation_cvo' => 'Cvo',
        	'volume_propose' => 'Volume total proposé*:',
        	'poids' => 'Poids*:',
            'prix_unitaire' => 'Prix unitaire net HT*:',
        	'type_prix_1' => 'Type de prix*:',
        	'type_prix_2' => 'Préciser*:',
        	'determination_prix' => 'Modalité de fixation du prix définitif ou de révision du prix*: (celui-ci sera communiqué à l\'interprofession par les parties au contrat)',
        	'determination_prix_date' => 'Date de détermination du prix définitif*:',
        	'prix_total' => 'Prix total HT:',
        	'part_cvo' => 'Part CVO:',
        	'repartition_cvo_acheteur' => 'Repartition CVO acheteur:',
            'conditions_paiement' => 'Paiement*:',
        	'delai_paiement' => 'Delai de paiement*:',
        	'clause_reserve_retiraison' => 'Clause de reserve de propriété (si réserve, recours possible jusqu\'au paiement complet): ',
        	'date_debut_retiraison' => 'Date de début de retiraison*:',
        	'date_limite_retiraison' => 'Date limite de retiraison*:',
        	'vin_livre' => 'Le produit sera:',
        	'type_retiraison' => 'Type de retiraison/livraison*:',
        ));
        $min = ($this->getObject()->volume_enleve)? floor($this->getObject()->volume_enleve) : 0;
        $minErreur = ($min > 1)? $min.' hl ont déjà été enlevés pour ce contrat' : $min.' hl a déjà été enlevé pour ce contrat';
        $this->setValidators(array(
        	'has_cotisation_cvo' => new ValidatorPass(),
        	'volume_propose' => new sfValidatorNumber(array('required' => true, 'min' => $min), array('min' => $minErreur)),
        	'prix_unitaire' => new sfValidatorNumber(array('required' => true)),
        	'type_prix_1' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($typePrix1))),
            'type_prix_2' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($typePrix2))),
        	'determination_prix' => new sfValidatorString(array('required' => false)),
        	'determination_prix_date' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)),
        	'prix_total' => new sfValidatorNumber(array('required' => false)),
	        'part_cvo' => new sfValidatorNumber(array('required' => false)),
	        'repartition_cvo_acheteur' => new sfValidatorNumber(array('required' => false)),
            'conditions_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getConditionsPaiement()), 'multiple' => false)),
            'delai_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDelaisPaiement()))),
        	'clause_reserve_retiraison' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'date_debut_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'date_limite_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'vin_livre' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixVinLivre()))),
        	'type_retiraison' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixTypeRetiraison()))),
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

    	if ($this->getObject()->type_transaction != 'raisin') {
    	    $this->getWidget('prix_unitaire')->setLabel('Prix unitaire net HT hors cotisation*:');
    	} else {
    	    $this->getWidget('volume_propose')->setLabel('Quantité totale proposée*:');
    	    $this->getWidget('prix_unitaire')->setLabel('Prix unitaire net HT*:');
    	}

    	if ($this->getObject()->type_transaction != 'vrac') {
    	    unset($this['type_retiraison']);
    	}

        if ($this->getConfiguration()->isContratPluriannuelActif() && $this->getObject()->isPluriannuel()) {
            $this->configurePluriannuel();
        }
        if ($this->getConfiguration()->isContratPluriannuelActif() && $this->getObject()->isAdossePluriannuel() && $this->getObject()->volume_propose) {
            $widget = $this->getWidget('volume_propose');
            $widget->setAttribute('readonly', 'readonly');
        }

        $this->editablizeInputPluriannuel();
  		    $this->validatorSchema->setPostValidator(new VracMarcheValidator($this->getObject()));
    		$this->widgetSchema->setNameFormat('vrac_marche[%s]');
    }


    public function configurePluriannuel() {
        unset($this['prix_unitaire'], $this['prix_total_unitaire'], $this['type_prix_1'], $this['type_prix_2'], $this['date_limite_retiraison']);

        $this->setWidget('contractualisation', new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getContractualisationChoices(), 'multiple' => false)));
        $this->getWidget('contractualisation')->setLabel('Contractualisation sur:');
        $this->setValidator('contractualisation', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContractualisationChoices()), 'multiple' => false)));
		$this->setWidget('pourcentage_recolte', new sfWidgetFormInputFloat());
		$this->setValidator('pourcentage_recolte', new sfValidatorNumber(array('required' => false, 'max' => 100), array('max' => 'Vous ne pouvez pas dépasser 100%')));
		$this->getWidget('pourcentage_recolte')->setLabel('Pourcentage de la récolte');
		$this->setWidget('surface', new sfWidgetFormInputFloat());
		$this->setValidator('surface', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('surface')->setLabel('Surface concernée');
        $this->setWidget('pluriannuel_prix_plancher', new sfWidgetFormInputFloat());
        $this->setWidget('pluriannuel_prix_plafond', new sfWidgetFormInputFloat());
        $this->getWidget('pluriannuel_prix_plancher')->setLabel('Prix plancher');
        $this->getWidget('pluriannuel_prix_plafond')->setLabel('Prix plafond');
        $this->setValidator('pluriannuel_prix_plancher', new sfValidatorNumber(array('required' => true)));
        $this->setValidator('pluriannuel_prix_plafond', new sfValidatorNumber(array('required' => true)));

        $this->setWidget('pluriannuel_clause_indexation', new sfWidgetFormTextarea());
        $this->getWidget('pluriannuel_clause_indexation')->setLabel('Clause d\'indexation des prix');
        $this->setValidator('pluriannuel_clause_indexation', new sfValidatorString(array('required' => false)));

        $this->getValidator('volume_propose')->setOption('required', false);

    }

    public function getContractualisationChoices() {
        return array('volume' => 'Un volume', 'surface' => 'Une surface', 'recolte' => 'Une récolte');
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

        $this->getObject()->type_prix = ($values['type_prix_1'] == 'non_definitif' && isset($values['type_prix_2']))? $values['type_prix_2'] : 'definitif';

        if (!in_array($this->getObject()->type_prix, $this->getTypePrixNeedDetermination())) {
          $this->getObject()->determination_prix = null;
          $this->getObject()->determination_prix_date = null;
        }
    	if ($this->getObject()->type_transaction == 'raisin') {
    		$this->getObject()->poids = $this->getObject()->volume_propose;
    	}
    	if (is_null($this->getObject()->type_retiraison)) {
    	    $this->getObject()->type_retiraison = 'vrac';
    	}

        $this->getObject()->update();
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();

      $this->setDefault('cepages', $this->getObject()->produit);

      if (is_null($this->getObject()->type_prix)) {
        $this->setDefault('type_prix', VracClient::PRIX_DEFAUT);
      }
      if ($this->getObject()->determination_prix_date) {
      	$d = new DateTime($this->getObject()->determination_prix_date);
        $this->setDefault('determination_prix_date', $d->format('d/m/Y'));
      }
      if (is_null($this->getObject()->clause_reserve_retiraison)) {
        $this->setDefault('clause_reserve_retiraison', 0);
      }
      if (is_null($this->getObject()->vin_livre)) {
        $this->setDefault('vin_livre', VracClient::STATUS_VIN_RETIRE);
      }
      if (is_null($this->getObject()->type_retiraison) && $this->getObject()->type_transaction == 'vrac' && !$this->getObject()->isPluriannuel()) {
        $this->setDefault('type_retiraison', 'vrac');
      }
      if (in_array($this->getObject()->type_prix, array('objectif', 'acompte'))) {
          $this->setDefault('type_prix_1', 'non_definitif');
          $this->setDefault('type_prix_2', $this->getObject()->type_prix);
      } else {
          $this->setDefault('type_prix_1', 'definitif');
          $this->setDefault('type_prix_2', null);
      }
      if ($this->getObject()->pourcentage_recolte) {
          $this->setDefault('contractualisation', 'recolte');
      } elseif ($this->getObject()->surface) {
          $this->setDefault('contractualisation', 'surface');
      } else {
          $this->setDefault('contractualisation', 'volume');
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

    public function isConditionneDelaiPaiement()
    {
        return false;
    }

    public function conditionneIVSE() {
      return false;
    }

    public function hasAcompteInfo() {
      return true;
    }

    public function getDelaisPaiement()
    {
        $delais = parent::getDelaisPaiement();
        if ($this->getConfiguration()->isContratPluriannuelActif() && $this->getObject()->isPluriannuel()) {
            $delais[null] = 'Les parties fixeront les délais de paiement dans chacun des contrats d\'application';
        }
    	return $delais;
    }

    public function getChoixTypeRetiraison() {
        $choices = parent::getChoixTypeRetiraison();
        if ($this->getConfiguration()->isContratPluriannuelActif() && $this->getObject()->isPluriannuel()) {
            $choices[null] = 'Les parties fixeront le type de retiraison dans chacun des contrats d\'application';
        }
        return $choices;
    }
}
