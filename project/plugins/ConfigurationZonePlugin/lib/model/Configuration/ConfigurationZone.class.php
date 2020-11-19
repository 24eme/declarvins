<?php
/**
 * Model for Configuration
 *
 */

class ConfigurationZone extends BaseConfigurationZone
{
	const PREFIXE_ID = 'CONFIGURATION-ZONE';

	public function constructId()
    {
        $this->set('_id', self::PREFIXE_ID.'-'.$this->identifiant);
    }

    public function getConfigurationProduits()
    {
			$current = ConfigurationClient::getConfiguration();
    	$produits = array();
    	foreach ($this->liaisons as $interproId) {
				if ($current->produits->exist($interproId)) {
    			$produits[$current->produits->get($interproId)] = ConfigurationProduitClient::getInstance()->find($current->produits->get($interproId));
				}
    	}
    	return $produits;
    }

    protected function getInterprosFor($node)
    {
    	$result = array();
    	$interpros = $this->get($node);
    	foreach ($interpros as $interpro) {
    		$result[$interpro] = InterproClient::getInstance()->find($interpro);
    	}
    	return $result;
    }

    public function getInterprosForInscriptions()
    {
    	return $this->getInterprosFor('inscriptions');
    }

    public function getInterprosForAccessibilites()
    {
    	return $this->getInterprosFor('accessibilites');
    }

    public function getInterprosForLiaisons()
    {
    	return $this->getInterprosFor('liaisons');
    }
}
