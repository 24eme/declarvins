<?php
/**
 * Model for DRMDetails
 *
 */

class DRMDetails extends BaseDRMDetails {

	public function getProduit($labels = array()) {
		$slug = $this->slugifyLabels($labels);
		if (!$this->exist($slug)) {

			return false;
		}

		return $this->get($slug);
	}

	public function addProduit($labels = array()) {
		return $this->add($this->slugifyLabels($labels));
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