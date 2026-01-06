<?php
class VracConditionIrForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['annexe']);
        unset($this['premiere_mise_en_marche']);
        unset($this['bailleur_metayer']);

        $this->setWidget('ramasseur_raisin', new sfWidgetFormChoice(array('choices' => array('vendeur' => 'ramassé par le vendeur', 'acheteur' => 'ramassé par l\'acheteur'),'expanded' => true)));
        $this->getWidget('ramasseur_raisin')->setLabel('Le raisin sera:');
        $this->setValidator('ramasseur_raisin', new sfValidatorChoice(array('required' => true, 'choices' => array('vendeur','acheteur'))));

        if ($this->getObject()->tiersIsPacteCooperatif()) {
            $this->getWidget('cas_particulier')->setAttribute('readonly', 'readonly');
            $this->getWidget('type_transaction')->setAttribute('readonly', 'readonly');
        }
        $this->validatorSchema->setPostValidator(new VracConditionIrValidator($this->getObject()));
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      if (is_null($this->getObject()->ramasseur_raisin)) {
        $this->setDefault('ramasseur_raisin', 'vendeur');
      }
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->cas_particulier == "aucune" || $this->getObject()->cas_particulier == "interne") {
             $this->getObject()->type_contrat = VracClient::TYPE_CONTRAT_EGALIM;
        }
        else {
            $this->getObject()->type_contrat = null;
        }
    }


    public function getCasParticulier() {
    	$options = parent::getCasParticulier();
        if (!$this->getObject()->tiersIsPacteCooperatif()) {
            unset($options['union']);
        }
        return $options;
    }
}
