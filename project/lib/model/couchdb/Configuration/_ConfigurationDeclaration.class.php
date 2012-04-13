<?php
/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */

abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree implements ConfigurationInterface {
	
	const TYPE_NOEUD = null;

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

	public function getCodes() {

		return $this->store('codes', array($this, 'getCodesAbstract'));
	}

	protected function getCodesAbstract() {
		$codes = $this->getDocument()->getProduitCodes($this->getHash());
		if ($codes !== null) {

			return $codes;
		} else {

			return $this->getParentNode()->getCodes().$this->getCode();
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
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
}