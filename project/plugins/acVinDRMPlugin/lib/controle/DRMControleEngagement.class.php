<?php
class DRMControleEngagement extends DRMControle
{
	const TYPE = 'engagement';
	public function __construct($code, $lien = null, $libelle = null) 
	{		
		parent::__construct(self::TYPE, self::TYPE.'_'.$code, $lien);
	}
}