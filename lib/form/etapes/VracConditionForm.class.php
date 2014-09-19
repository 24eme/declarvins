<?php
class VracConditionForm extends VracForm 
{
   	public function configure()
    {
  		$this->setWidgets(array(
        	'date_debut_retiraison' => new sfWidgetFormInputText(),
        	'date_limite_retiraison' => new sfWidgetFormInputText(),
        	'conditions_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getConditionsPaiement(), 'multiple' => false)),
        	'vin_livre' => new sfWidgetFormChoice(array('choices' => $this->getChoixVinLivre(),'expanded' => true)),
        	'reference_contrat_pluriannuel' => new sfWidgetFormInputText(),
        	'delai_paiement' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getDelaisPaiement())),
        	'clause_reserve_retiraison' => new sfWidgetFormInputCheckbox()
    	));
        $this->widgetSchema->setLabels(array(
        	'date_debut_retiraison' => 'Date de début de retiraison*:',
        	'date_limite_retiraison' => 'Date limite de retiraison*:',
        	'conditions_paiement' => 'Conditions générales de vente*:',
        	'vin_livre' => 'Le vin sera*:',
        	'reference_contrat_pluriannuel' => 'Référence contrat pluriannuel:',
        	'delai_paiement' => 'Delai de paiement*:',
        	'clause_reserve_retiraison' => 'Clause de reserve de propriété (si réserve, recours possible jusqu\'au paiement complet): '
        ));
        $this->setValidators(array(
        	'date_debut_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'date_limite_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'conditions_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getConditionsPaiement()), 'multiple' => false)),
        	'vin_livre' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixVinLivre()))),
        	'reference_contrat_pluriannuel' => new sfValidatorString(array('required' => false)),
        	'delai_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDelaisPaiement()))),
        	'clause_reserve_retiraison' => new ValidatorPass()
        ));
        
        $paiements = new VracPaiementCollectionForm($this->vracPaiementFormName(), $this->getObject()->paiements);
        $this->embedForm('paiements', $paiements);
  		
  		
		$this->setWidget('volume_propose', new sfWidgetFormInputHidden());
		$this->setValidator('volume_propose', new ValidatorPass());
  		$this->validatorSchema->setPostValidator(new VracConditionValidator());
  		$this->widgetSchema->setNameFormat('vrac_condition[%s]');
    }

    protected function doUpdateObject($values) {
      if ($values['conditions_paiement'] != VracClient::ECHEANCIER_PAIEMENT) {
        $values['paiements'] = array();
        $this->getObject()->remove('paiements');
        $this->getObject()->add('paiements');
      }
      $this->getObject()->conditions_paiement_libelle = $this->getConfiguration()->formatConditionsPaiementLibelle(array($this->getObject()->conditions_paiement));
      parent::doUpdateObject($values); 
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      if (is_null($this->getObject()->vin_livre)) {
        $this->setDefault('vin_livre', VracClient::STATUS_VIN_RETIRE);
      }  
    }


    public function getCgpEcheancierNeedDetermination() {
      return 'echeancier_paiement';
    }

    public function getCgpContratNeedDetermination() {
      return 'contrat_pluriannuel';
    }


    public function conditionneReferenceContrat() {
      return true;
    }
}