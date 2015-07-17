<?php
class DRMControleError extends DRMControle
{
	const TYPE = 'error';
	public function __construct($code, $lien, $libelle = null) {
		parent::__construct(self::TYPE, self::TYPE.'_'.$code, $lien, $libelle);
	}
	
}