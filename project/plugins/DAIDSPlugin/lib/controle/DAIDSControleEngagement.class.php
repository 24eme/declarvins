<?php
class DAIDSControleEngagement extends DAIDSControle
{
	const TYPE = 'engagement';
	public function __construct($code) 
	{		
		parent::__construct(self::TYPE, self::TYPE.'_'.$code, null);
	}
}