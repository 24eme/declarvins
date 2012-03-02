<?php

class DRMAppellationAjoutForm extends acCouchdbFormDocumentJson {

    protected $_interpro = null;
    protected $_choices_appellation = null;

    public function __construct(acCouchdbJson $object, $interpro, $options = array(), $CSRFSecret = null) {
        $this->_interpro = $interpro;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function setup() {
        $this->setWidgets(array(
            'appellation' => new sfWidgetFormChoice(array('choices' => $this->getProduitsAppellations())),
        ));

        $this->setValidators(array(
            'appellation' => new sfValidatorChoice(array('choices' => array_keys($this->getProduitsAppellations()), 'required' => true), array('required' => "Aucune appellation n'a été saisi !")),
        ));

        $this->widgetSchema->setNameFormat('drm_appellation_ajout[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function getProduitsAppellations() {
        if (is_null($this->_choices_appellation)) {
            $config_certification = ConfigurationClient::getCurrent()->declaration
                                                    ->certifications
                                                    ->get($this->getObject()->getKey());

            $produits = $config_certification->getProduitsAppellations($this->_interpro);

            $this->_choices_appellation = array("" => "");
            foreach($produits as $hash => $libelles)  {
                $libelle = implode(' ', array_filter($libelles));
                preg_match('|declaration/certifications/.+/appellations/(.+)|', $hash, $matches);
                $appellation_key = $matches[1];
                $this->_choices_appellation[$appellation_key] = $libelle;
            }
        }

        return $this->_choices_appellation;
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