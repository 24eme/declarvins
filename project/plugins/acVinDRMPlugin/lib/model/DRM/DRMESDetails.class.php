<?php

/**
 * Model for DRMESDetails
 *
 */
class DRMESDetails extends BaseDRMESDetails {

	protected function update($params = array()) {
		parent::update($params);
	}
	
    public function getNoeud() {

        return $this->getParent();
    }

    public function getTotalHash() {
        return str_replace('_details', '', $this->getKey());
    }

    public function getProduitDetail() {

        return $this->getParent()->getParent();
    }

    protected function init($params = array()) {
        parent::init($params);

        $this->getParent()->remove($this->getKey());
        $this->getParent()->add($this->getKey());
    }

}
