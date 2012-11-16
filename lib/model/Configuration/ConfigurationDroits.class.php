<?php
/**
 * Model for ConfigurationDroits
 *
 */

class ConfigurationDroits extends BaseConfigurationDroits {
	
	const CODE_CVO = 'CVO';
	const LIBELLE_CVO = 'Cvo';
	const PART_ACHETEUR = 0.5;
	
	public function addDroit($date, $taux, $code, $libelle) {
	  $value = $this->add();
	  $value->date = $date;
	  $value->taux = $taux;
	  $value->code = $code;
	  $value->libelle = $libelle;
	}
	
	public function getCurrentDroit($periode) {
		$currentDroit = null;
		foreach ($this as $configurationDroit) {
			$date = new DateTime($configurationDroit->date);
			if ($periode >= $date->format('Y-m')) {
				if ($currentDroit) {
					if ($date->format('Y-m-d') > $currentDroit->date) {
						$currentDroit = $configurationDroit;
					}
				} else {
					$currentDroit = $configurationDroit;
				}
			}
		}
		if ($currentDroit) {
			return $currentDroit;
		} else {
			return null;
		}
	}
	
	public function getInterpro() {
		return $this->getParent()->getParent();
	}

}