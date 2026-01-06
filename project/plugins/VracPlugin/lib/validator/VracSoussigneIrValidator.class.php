<?php

class VracSoussigneIrValidator extends VracSoussigneValidator {


    protected $vrac = null;

    public function __construct($vrac, $options = array(), $messages = array())
    {
        if ($vrac) {
            $this->vrac = $vrac;
        }
        parent::__construct($options, $messages);
    }

    public function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);
        $this->addMessage('vendeur_union', "L'apport contractuel à une union n'est possible que si le vendeur est une coopérative");
        $this->addMessage('acheteur_union', "L'apport contractuel à une union n'est possible que si l'acheteur est une union");
    }

    protected function doClean($values) {
        parent::doClean($values);
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;

        if ($this->vrac->cas_particulier == 'union') {
            $vendeur = EtablissementClient::getInstance()->find($values['vendeur_identifiant']);
            if ($vendeur->sous_famille != EtablissementFamilles::SOUS_FAMILLE_CAVE_COOPERATIVE) {
                $errorSchema->addError(new sfValidatorError($this, 'vendeur_union'), 'vendeur_identifiant');
                $hasError = true;
            }
            $acheteur = EtablissementClient::getInstance()->find($values['acheteur_identifiant']);
            if ($acheteur->sous_famille != EtablissementFamilles::SOUS_FAMILLE_UNION) {
                $errorSchema->addError(new sfValidatorError($this, 'acheteur_union'), 'acheteur_identifiant');
                $hasError = true;
            }
        }

        if ($hasError) {
                throw new sfValidatorErrorSchema($this, $errorSchema);
        }

        return $values;
    }

}
