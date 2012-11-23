<?php
class DAIDSControleError extends DAIDSControle
{
	const TYPE = 'error';
	public function __construct($code, $lien) {
		parent::__construct(self::TYPE, self::TYPE.'_'.$code, $lien);
	}
	
}