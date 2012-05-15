<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {

	public function getLibelle() {

	  	return $this->getConfig()->getLibelle();
	}
	
	public function getCode() {
	  
	  	return $this->getConfig()->getCode();
	}

	public function getChildrenNode() {

		return $this->genres;
	}
}