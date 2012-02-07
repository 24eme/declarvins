<?php
/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */

abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree {

	protected function loadAllData() {
		parent::loadAllData();
        $this->getLibelles();
    }

	public function getLibelles() {
		return $this->store('libelles', array($this, 'getLibellesAbstract'));
	}

	protected function getLibellesAbstract() {

		return array_merge($this->getParent()->getParent()->getLibelles(), 
						   array($this->getKey() => $this->libelle));
	}
}