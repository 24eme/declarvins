<?php
class VracMarcheCivpForm extends VracMarcheForm 
{

    public function configure() {
        parent::configure();

        $this->getWidget('conditions_paiement')->setLabel('Conditions de vente:');
        $this->getValidator('conditions_paiement')->setOption('required', false);
        
        $this->getWidget('conditions_paiement')->setOption('multiple', true);
        $this->getValidator('conditions_paiement')->setOption('multiple', true);
    }
    protected function doUpdateObject($values) {
        if (isset($values['conditions_paiement']) && !empty($values['conditions_paiement']) && is_array($values['conditions_paiement'])) {
            $values['conditions_paiement'] = current($values['conditions_paiement']);
        }
    	parent::doUpdateObject($values);
    	$this->getObject()->has_cotisation_cvo = 0;
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      $this->setDefault('has_cotisation_cvo', 0);

    }
}