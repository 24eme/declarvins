<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {

	public function getAppellationsChoices(array $exclude_key = array())
	{
        $choices = array();
        foreach ($this->appellations as $appellation_key => $appellation) {
            if (!in_array($appellation_key, $exclude_key)) {
                $choices[$appellation_key] = $appellation->getLibelle();
            }
        }
        
        return $choices;	
	}

    public function getCouleursChoices(array $exclude_key = array())
    {
        $choices = array();
        foreach ($this->appellations as $appellation_key => $appellation) {
            foreach ($appellation->couleurs as $couleur_key => $couleur) {
                if (!in_array($couleur_key, $exclude_key)) {
                    $choices[$appellation_key][$appellation_key.'/'.$couleur_key] = $couleur->getLibelle();
                }
            }
        }
        
        return $choices;    
    }

    public function getCepagesChoices(array $exclude_key = array())
    {
        $choices = array();
        foreach ($this->appellations as $appellation_key => $appellation) {
            foreach ($appellation->couleurs as $couleur_key => $couleur) {
                foreach ($couleur->cepages as $cepage_key => $cepage) {
                    if (!in_array($couleur_key, $exclude_key)) {
                        $choices[$appellation_key.'/'.$couleur_key][$appellation_key.'/'.$couleur_key.'/'.$cepage_key] = $cepage->getLibelle();
                    }
                }
            }
        }
        //print_r($choices);
        return $choices;    
    }
}