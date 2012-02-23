<?php

class DRMAppellationAjoutForm extends acCouchdbFormDocumentJson {

    protected $_appellation_choices = null;

    public function setup() {
        $this->setWidgets(array(
            'appellation_autocomplete' => new sfWidgetFormInputText(array('label' => 'Appellation'), array('autocomplete-data' => json_encode($this->getProduitsAppellations()))),
            'appellation' => new sfWidgetFormInputHidden(array()),
        ));

        $this->setValidators(array(
            'appellation_autocomplete' => new sfValidatorString(array('required' => true)),
            'appellation' => new sfValidatorString(array('required' => true)),
        ));

        $this->widgetSchema->setNameFormat('drm_appellation_ajout[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function getProduitsAppellations() {
        $config_certification = ConfigurationClient::getCurrent()->declaration
                                                ->certifications
                                                ->get($this->getObject()->getKey());

        $produits = $config_certification->getProduitsAppellations('INTERPRO-inter-rhone');

        $produits_flat = array();
        foreach($produits as $hash => $libelles)  {
            $libelle = implode(' ', array_filter($libelles));
            preg_match('|declaration/certifications/.+/appellations/(.+)|', $hash, $matches);
            $appellation_key = $matches[1];
            $produits_flat[] = $appellation_key.'|@'.$libelle;
        }

        return $produits_flat;
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