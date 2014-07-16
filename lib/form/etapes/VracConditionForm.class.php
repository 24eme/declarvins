<?php
class VracConditionForm extends VracForm 
{
   	public function configure()
    {
  		parent::configure();
  		$this->useFields(array(
  	       'date_debut_retiraison',
  	       'date_limite_retiraison',
           'conditions_paiement',
  	       'vin_livre',
           'reference_contrat_pluriannuel',
  	       'delai_paiement',
           'clause_reserve_retiraison',
  		     'paiements',
		   'volume_propose'
  		));
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