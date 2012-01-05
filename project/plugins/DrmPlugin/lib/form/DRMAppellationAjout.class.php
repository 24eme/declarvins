<?php

class DRMAppellationAjoutForm extends acCouchdbFormDocumentJson {

    protected $_appellation_choices = null;

    public function setup() {

        if ($this->getObject()->getDefinition()->getModel() . $this->getObject()->getDefinition()->getHash() != 'DRM/declaration/certifications/*/appellations') {
            throw new sfException("Object must be a DRM/declaration/certifications/*/appellations object");
        }

        $this->setWidgets(array(
            'appellation' => new sfWidgetFormChoice(array('choices' => $this->getAppellationChoices())),
        ));

        $this->setValidators(array(
            'appellation' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAppellationChoices()))),
        ));

        $this->widgetSchema->setNameFormat('drm_appellation_ajout[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function getAppellationChoices() {
        if (is_null($this->_appellation_choices)) {
            $this->_appellation_choices = array('' => '');
            foreach (ConfigurationClient::getCurrent()->declaration->certifications->get($this->getObject()->getParent()->getKey())->appellations as $key => $item) {
                //if (!$this->getObject()->exist($key)) {
                $this->_appellation_choices[$key] = $item->getLibelle();
                //}
            }
        }

        return $this->_appellation_choices;
    }

    public function doUpdateObject($values) {
        $this->getObject()->add($values['appellation']);
    }

    public function getAppellation() {
        if ($this->isValid()) {
            return $this->getObject()->get($this->values['appellation']);
        }

        return null;
    }

}