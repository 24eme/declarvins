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

			return array_merge($this->getParentNode()->getLibelles(), 
						   array($this->libelle));
		}
	}

	public function getCode() {
		if ($this->getKey() == Configuration::DEFAULT_KEY) {
			return null;
		}
		return $this->getKey();
	}

	public function getCodes() {

		return $this->store('codes', array($this, 'getCodesAbstract'));
	}

	protected function getCodesAbstract() {
		try {
			return array_merge($this->getParentNode()->getCodes(), 
				   array($this->getCode()));
		} catch (Exception $e) {
			return array($this->getCode());
		}
	}

	public function getParentNode() {
		$parent = $this->getParent()->getParent();
		if ($parent->getKey() == 'declaration') {
			throw new sfException('Noeud racine atteint');
		} else {
			return $this->getParent()->getParent();
		}
	}
}