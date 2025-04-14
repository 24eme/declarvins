<?php
class VracConditionForm extends VracForm
{
   	public function configure()
    {
  		$this->setWidgets(array(
  		    'is_contrat_pluriannuel' => new sfWidgetFormChoice(array('choices' => array('0' => 'Ponctuel', '1' => 'Adossé à un contrat pluriannel cadre'),'expanded' => true)),
        	'reference_contrat_pluriannuel' => new sfWidgetFormInputText(),
            'duree_contrat_pluriannuel' => new sfWidgetFormInputText(),
  		    'cas_particulier' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getCasParticulier(), 'renderer_options' => array('formatter' => array('VracSoussigneForm', 'casParticulierFormatter')))),
        	'export' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
    		'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'bailleur_metayer' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'annexe' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'type_transaction' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesTransaction())),
    	));
        $this->widgetSchema->setLabels(array(
        	'is_contrat_pluriannuel' => 'Type de contrat:',
        	'reference_contrat_pluriannuel' => 'Référence du contrat pluriannuel cadre adossé à ce contrat*:',
            'duree_contrat_pluriannuel' => 'Durée en année du contrat pluriannuel*:',
            'cas_particulier' => 'Condition particulière*:',
        	'export' => 'Expédition export*:',
        	'premiere_mise_en_marche' => 'Première mise en marché:',
            'bailleur_metayer' => 'Entre bailleur et métayer:',
        	'annexe' => 'Présence d\'une annexe (cahier des charges techniques):',
        	'type_transaction' => 'Type de produit:',
        ));
        $this->setValidators(array(
            'is_contrat_pluriannuel' => new sfValidatorChoice(array('required' => true, 'choices' => array('0','1'))),
        	'reference_contrat_pluriannuel' => new sfValidatorString(array('required' => false)),
            'duree_contrat_pluriannuel' => new sfValidatorInteger(array('required' => false, 'min' => 2), array('min' => 'Un contrat pluriannuel à une durée minimale de 2 ans')),
            'cas_particulier' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCasParticulier()))),
        	'export' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'premiere_mise_en_marche' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
            'bailleur_metayer' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'annexe' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'type_transaction' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesTransaction()))),
        ));


        if (count($this->getTypesTransaction()) < 2) {
            unset($this['type_transaction']);
        }

        if ($this->getConfiguration()->isContratPluriannuelActif()) {
            unset($this['reference_contrat_pluriannuel'], $this['duree_contrat_pluriannuel'], $this['is_contrat_pluriannuel']);
            if ($this->getObject()->isPluriannuel()) {
                $this->configurePluriannuel();
            }
            if ($this->getObject()->isAdossePluriannuel()) {
                $this->setWidget('campagne_courante', new sfWidgetFormInputText());
                $this->getWidget('campagne_courante')->setLabel('Campagne d\'application');
                $this->setValidator('campagne_courante', new sfValidatorString());
            }
        }

        $this->editablizeInputPluriannuel();
  		$this->validatorSchema->setPostValidator(new VracConditionValidator());
  		$this->widgetSchema->setNameFormat('vrac_condition[%s]');
    }

    public function configurePluriannuel() {

        $this->setWidget('pluriannuel_campagne_debut', new sfWidgetFormChoice(array('choices' => $this->getCampagneChoicesDebut())));
        $this->setWidget('pluriannuel_campagne_fin', new sfWidgetFormChoice(array('choices' => $this->getCampagneChoicesFin())));

        $this->getWidget('pluriannuel_campagne_debut')->setLabel('Conclu à partir de la campagne');
        $this->getWidget('pluriannuel_campagne_fin')->setLabel('Pour une durée de');

        $this->setValidator('pluriannuel_campagne_debut', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCampagneChoicesDebut()))));
        $this->setValidator('pluriannuel_campagne_fin', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCampagneChoicesFin()))));

    }

    public function getCampagneChoicesDebut() {
        $campagnes = array();
        for($d=date('Y'),$i=$d-2;$i<$d+2;$i++) {
            $campagnes[$i.'-'.($i+1)] = $i.'-'.($i+1);
        }
        return $campagnes;
    }

    public function getCampagneChoicesFin() {
        $keys = range(2, 5);
        $values = array_map(function ($v) {
            return $v." ans";
        }, $keys);

        return array_combine($keys, $values);
    }

    protected function doUpdateObject($values) {
      if (isset($values['is_contrat_pluriannuel']) && !$values['is_contrat_pluriannuel']) {
          $values['reference_contrat_pluriannuel'] = null;
          $values['duree_contrat_pluriannuel'] = null;
      }
      parent::doUpdateObject($values);
      if (isset($values['is_contrat_pluriannuel'])) {
          $this->getObject()->contrat_pluriannuel = $values['is_contrat_pluriannuel'];
      }
      if (!$this->getObject()->annexe) {
          $this->getObject()->annexe = 0;
      }
        $types_transaction = $this->getTypesTransaction();
        if (count($types_transaction) == 1) {
            foreach($types_transaction as $key => $value) {
                $this->getObject()->type_transaction = $key;
            }
        }
        $this->getObject()->type_transaction_libelle = $this->getConfiguration()->formatTypesTransactionLibelle(array($this->getObject()->type_transaction));
        $this->getObject()->cas_particulier_libelle = $this->getConfiguration()->formatCasParticulierLibelle(array($this->getObject()->cas_particulier));
        $this->getObject()->initClauses();

        if ($this->getObject()->isPluriannuel()) {
            $campagne_debut = $this->getObject()->pluriannuel_campagne_debut;
            $annees = explode('-', $campagne_debut);
            $campagne_fin = implode('-', [$annees[0] + $values['pluriannuel_campagne_fin'] - 1, $annees[0] + $values['pluriannuel_campagne_fin']]);
            $this->getObject()->pluriannuel_campagne_fin = $campagne_fin;
        }

    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('cas_particulier', (($this->getObject()->cas_particulier) ? $this->getObject()->cas_particulier : null));
      if (is_null($this->getObject()->export)) {
          $this->setDefault('export', 0);
      }
      if (is_null($this->getObject()->annexe)) {
        $this->setDefault('annexe', 0);
      }
      if (is_null($this->getObject()->bailleur_metayer)) {
        $this->setDefault('bailleur_metayer', 0);
      }
      if ($this->getConfiguration()->isContratPluriannuelActif()) {
          if ($this->getObject()->isPluriannuel()) {
              $cm = new CampagneManager('08-01');
              if (!$this->getObject()->pluriannuel_campagne_debut) {
                  $this->setDefault('pluriannuel_campagne_debut', $cm->getCurrent());
              }
              if ($this->getObject()->pluriannuel_campagne_fin) {
                  $this->setDefault('pluriannuel_campagne_fin', intval($this->getObject()->pluriannuel_campagne_fin) - intval($this->getObject()->pluriannuel_campagne_debut) + 1);
              } else {
                  $this->setDefault('pluriannuel_campagne_fin', 2);
              }
          }

          if (!$this->getObject()->isAdossePluriannuel()) {
            $this->setDefault('is_contrat_pluriannuel', '0');
          } else {
              $this->setDefault('is_contrat_pluriannuel', '1');
          }
      } else {
          $this->setDefault('is_contrat_pluriannuel', ($this->getObject()->contrat_pluriannuel)? 1 : 0);
      }

      if (is_null($this->getObject()->type_transaction)) {
        $this->setDefault('type_transaction', VracClient::TRANSACTION_DEFAUT);
      }
      $this->setDefault('campagne_courante', (new CampagneManager('08-01'))->getCurrent());
    }

    public function conditionneIVSE() {
      return false;
    }
}
