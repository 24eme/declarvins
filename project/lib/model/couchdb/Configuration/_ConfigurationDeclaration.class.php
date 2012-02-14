<?php
/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */

abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree {

	protected function loadAllData() {
		parent::loadAllData();
    }

	public function getLibelles() {
		return $this->store('libelles', array($this, 'getLibellesAbstract'));
	}

	protected function getLibellesAbstract() {
		$libelle = $this->getDocument()->getProduitLibelles($this->getHash());
		if ($libelle !== null) {

			return $libelle;
		} else {

			return array_merge($this->getParent()->getParent()->getLibelles(), 
						   array($this->getKey() => $this->libelle));
		}
	}
}