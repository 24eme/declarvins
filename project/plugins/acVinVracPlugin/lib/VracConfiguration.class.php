<?php
class VracConfiguration
{
	private static $_instance = null;

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new VracConfiguration();
		}
		return self::$_instance;
	}

	public function __construct()
	{
	}

	public function getContenances()
	{
		$config = ConfigurationClient::getConfiguration(date('Y-m-d'));
		$centilisations = $config->crds->centilisation->toArray();
		if (isset($centilisations['AUTRE']))
			unset($centilisations['AUTRE']);
		foreach ($centilisations as $k => $v) {
			if (preg_match('/CL_/', $k)) {
				$centilisations[$k] = 'Bouteille '.$v;
			}
		}
		return $centilisations;
	}
}