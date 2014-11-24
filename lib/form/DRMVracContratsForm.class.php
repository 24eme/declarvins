<?php
class DRMVracContratsForm extends acCouchdbObjectForm
{

        public function configure()
        {
                $choices = $this->getContratChoices();
        	$contrats = new DRMVracContratCollectionForm($this->getObject()->vrac, $choices);
        	$this->embedForm('contrats', $contrats);
    }

        public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form) {
            if($form instanceof FormBindableInterface) {
                $form->bind($taintedValues[$key], $taintedFiles[$key]);
                $this->updateEmbedForm($key, $form);
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }

    public function getContratChoices()
    {
           $contrat_choices = $this->getObject()->getContratsVracAutocomplete();
           $contrat_choices[''] = '';
           ksort($contrat_choices);
        return $contrat_choices;
    }

    public function updateEmbedForm($name, $form) {
        $this->widgetSchema[$name] = $form->getWidgetSchema();
        $this->validatorSchema[$name] = $form->getValidatorSchema();
    }

    public function update($values)
    {
        foreach ($this->embeddedForms as $key => $form) {
                $form->update($values[$key]);
        }
    }

}