<?php

class DRMAppellationAjoutForm extends BaseForm {

    protected $_interpro = null;
    protected $_drm = null;
    protected $_config = null;
    protected $_choices_appellation = null;

    public function __construct(DRM $drm, _ConfigurationDeclaration $config, $CSRFSecret = null) {
        $this->_drm = $drm;
        $this->_interpro = $drm->getInterpro();
        $defaults = array();
        parent::__construct($defaults, $options, $CSRFSecret);
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

            $produits = $config_certification->getProduitsAppellations($this->_interpro, $this->getObject()->getDocument()->getDepartement());

            $this->_choices_appellation = array("" => "");
            foreach($produits as $hash => $libelles)  {
                $libelle = implode(' ', array_filter($libelles));
                /*preg_match('|declaration/certifications/.+/appellations/(.+)|', $hash, $matches);
                $appellation_key = $matches[1];*/
                $this->_choices_appellation[$hash] = $libelle;
            }
        }

        return $this->_choices_appellation;
    }

    public function doUpdateObject($values) {
        $appellation = $this->getDelaration();
        $this->getObject()->getDocument()->produits->get($appellation->getGenre()->getCertification()->getKey())
                                                   ->add($appellation->getGenre()->getKey())
                                                   ->add($appellation->getKey());
        //print_r($this->getObject()->getDocument()->toJson());
        //exit;
    }

    public function getDelaration() {
        if($this->isValid()) {
            return $this->getObject()->getDocument()->getOrAdd($this->values['appellation']);
        }

        return null;
    }

}