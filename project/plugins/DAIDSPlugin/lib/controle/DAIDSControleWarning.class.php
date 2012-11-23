<?php
class DAIDSControleWarning extends DAIDSControle
{
	const TYPE = 'warning';
	public function __construct($code, $lien) {
		parent::__construct(self::TYPE, self::TYPE.'_'.$code, $lien);
	}
}