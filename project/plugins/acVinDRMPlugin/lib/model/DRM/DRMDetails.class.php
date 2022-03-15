<?php
/**
 * Model for DRMDetails
 *
 */

class DRMDetails extends BaseDRMDetails {

	public function getProduit($labels = array(), $complement_libelle = null) {
		$slug = self::hashifyLabels($labels, $this->checkComplementLibelle($complement_libelle));
		$keys = array_keys($this->toArray());
		foreach ($keys as $key) {
		    if ($slug == $key) {
		        return $this->get($key);
		    }
		}

		if (!$this->exist($slug)) {

			return false;
		}

		return $this->get($slug);
	}

	public function addProduit($labels = array(), $complement_libelle = null) {
		$detail = $this->add(self::hashifyLabels($labels, $this->checkComplementLibelle($complement_libelle)));
		$lab = array();
		foreach ($labels as $label) {
			$lab[] = $label;
		}
		$detail->labels = $lab;
		$detail->storeInterpro();
		if ($config = $detail->getConfig()) {
			$detail->has_vrac = $config->getCurrentDrmVrac(true);
		}
		return $detail;
	}

	public static function hashifyLabels($labels, $complement_libelle = null) {
		if (!$complement_libelle) {
			return KeyInflector::slugify(self::getLabelKeyFromArray($labels));
		}
		return md5(KeyInflector::slugify(self::getLabelKeyFromArray($labels)).$complement_libelle);
	}

	protected function checkComplementLibelle($complement_libelle = null) {
			if (trim($complement_libelle) == trim($this->getParent()->getLibelle())) {
				$complement_libelle = null;
			}
			return $complement_libelle;
	}

	protected static function getLabelKeyFromArray($labels) {
        $key = null;
        if ($labels && is_array($labels) && count($labels) > 0) {
           sort($labels);
           $key = implode('-', $labels);
        }

        return ($key)? $key : DRM::DEFAULT_KEY;
    }
}
