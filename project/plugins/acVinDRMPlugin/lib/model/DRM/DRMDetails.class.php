<?php
/**
 * Model for DRMDetails
 *
 */

class DRMDetails extends BaseDRMDetails {

	public function getProduit($labels = array()) {
		$slug = $this->slugifyLabels($labels);
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

	public function addProduit($labels = array()) {
		$detail = $this->add($this->slugifyLabels($labels));
		$lab = array();
		foreach ($labels as $label) {
			if (!preg_match('/^[a-f0-9]{32}$/', $label)) {
				$lab[] = $label;
			}
		}
		$detail->labels = $lab;
		$detail->storeInterpro();
		if ($config = $detail->getConfig()) {
			$detail->has_vrac = $config->getCurrentDrmVrac(true);
		}
		return $detail;
	}

	protected function slugifyLabels($labels) {

		return KeyInflector::slugify($this->getLabelKeyFromArray($labels));
	}

	protected function getLabelKeyFromArray($labels) {
        $key = null;
        if ($labels && is_array($labels) && count($labels) > 0) {
           sort($labels);
           $key = implode('-', $labels);
        }
        
        return ($key)? $key : DRM::DEFAULT_KEY;
    }
}