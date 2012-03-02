<?php
/**
 * Model for ConfigurationDroits
 *
 */

class ConfigurationDroits extends BaseConfigurationDroits {
	
	public function addDroit($date, $ration, $code) {
		$value = $this->add();
        $value->date = $date;
        $value->ratio = $ration;
        $value->code = $code;
	}
	
	public function getCurrentDroit($campagne) {
		$currentDroit = null;
		foreach ($this as $configurationDroit) {
			$date = new DateTime($configurationDroit->date);
			if ($campagne >= $date->format('Y-m')) {
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
			try {
				$parent = $this->getInterpro()->getParent()->getParent()->getParentNode();
				return $parent->interpro->get($this->getInterpro()->getKey())->droits->get($this->getKey())->getCurrentDroit($campagne);
			} catch (sfException $e) {
				throw new sfException('Aucuns droits pour la campagne spécifiée');
			}
		}
	}
	
	public function getInterpro() {
		return $this->getParent()->getParent();
	}

}