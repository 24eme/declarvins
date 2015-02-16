<?php

class DRMVracValidator extends sfValidatorBase {
	
	const POURCENTAGE_MARGE = 0.20;

    public function configure($options = array(), $messages = array()) {
        $this->setMessage('invalid', "vous ne pouvez pas, pour un même produit, sélectionner plusieurs fois le même contrat.");
        $this->addMessage('sup', "vous ne pouvez pas indiquer un volume supérieur de plus de 20% au volume restant du contrat.");
        $this->addMessage('couple_volume', "vous devez associer un volume au contrat renseigné.");
        $this->addMessage('couple_contrat', "vous devez renseigner un contrat au volume saisi.");
    }

    protected function doClean($values) {
    	$contratIds = array();
    	foreach ($values as $key => $value) {
    		if (is_array($value)) {
	    		foreach ($value['contrats'] as $contrat) {
	    			if ($contrat['vrac'] && !$contrat['volume']) {
	    				throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'couple_volume')));
	    			}
	    			if (!$contrat['vrac'] && $contrat['volume']) {
	    				throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'couple_contrat')));
	    			}
	    			if (in_array($contrat['vrac'], $contratIds)) {
	    				throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'invalid')));
	    			}
	    			$contratVrac = VracClient::getInstance()->findByNumContrat($contrat['vrac']);
	    			if ($contratVrac) {
	    				$vol = $contratVrac->volume_propose - $contratVrac->volume_enleve;
	    				$marge = $contratVrac->volume_propose * self::POURCENTAGE_MARGE;
	    				if ($contrat['volume'] > ($vol + $marge)) {
	    					throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'sup')));
	    				}
	    			}
	    			$contratIds[] = $contrat['vrac'];
	    		}	
    		}
    	}
        return $values;
    }

}
