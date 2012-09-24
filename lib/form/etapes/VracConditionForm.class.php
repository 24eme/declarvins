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
		$this->setValidator('volume_propose', new sfValidatorPass());
  		$this->validatorSchema->setPostValidator(new VracDateLimiteValidator());
  		$this->validatorSchema->setPostValidator(new VracEcheancierVolumesValidator());
  		$this->widgetSchema->setNameFormat('vrac_condition[%s]');
    }

    protected function doUpdateObject($values) {
      /*if (!isset($values['echeancier_paiement']) || !$values['echeancier_paiement']) {
        $values['paiements'] = array();
        $this->getObject()->remove('paiements');
        $this->getObject()->add('paiements');
      }*/
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