<?php
class VracConfiguration
{
	private static $_instance = null;
    protected $configuration;

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new VracConfiguration();
		}
		return self::$_instance;
	}

	public function __construct()
	{
        if(!sfConfig::has('vrac_configuration_vrac')) {
			throw new sfException("La configuration pour les vrac n'a pas été définie pour cette application");
		}

        $this->configuration = sfConfig::get('vrac_configuration_vrac', array());
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

    public function getPrixAppellations() {

        return $this->configuration['prix_appellations'];
    }
}
