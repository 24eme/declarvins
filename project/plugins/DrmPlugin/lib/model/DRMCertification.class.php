<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {
	/**
      *
      * @return string
      */
	public function __toString() {
		return ConfigurationClient::getCurrent()
											->declaration
											->certifications
											->get($this->getKey())
											->libelle;
	}
}