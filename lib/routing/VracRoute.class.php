<?php

class VracRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    const ETABLISSEMENT_IDENTIFIANT_ADMIN = 'admin';

    protected $vrac = null;
    protected $etablissement = null;
    
    protected function getObjectForParameters($parameters) {
        if(isset($parameters['contrat'])) {
        	$contrat = explode('-', $parameters['contrat']);
        	$numero = (isset($contrat[0]))? $contrat[0] : null;
        	$version = (isset($contrat[1]))? $contrat[1] : null;
            $this->vrac = VracClient::getInstance()->findByNumContrat($numero, $version);
        }
        if (!$this->vrac) 
        {
            $this->vrac = new Vrac();
        }
        if ($parameters['identifiant'] != self::ETABLISSEMENT_IDENTIFIANT_ADMIN) {
            $this->etablissement = EtablissementClient::getInstance()->find($parameters['identifiant']);
        } else {
            $this->etablissement = false;
        }
        
    	if ($this->getEtablissement()) {
			if (isset($this->options['no_archive']) && $this->options['no_archive'] === true && ($this->getEtablissement()->statut == Etablissement::STATUT_ARCHIVE) && !sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
				$this->redirect('vrac_etablissement', array('identifiant' => $this->getEtablissement()->identifiant));
			}
      		$this->checkSecurity($this->getEtablissement());
    	}
		
        return $this->vrac;
    }

    protected function convertObjectToArray($object) {
        $etablissement = false;
		
        if (isset($object['etablissement'])) {
            $etablissement = $object['etablissement'];
        }

        unset($object['etablissement']);

        return array_merge(parent::convertObjectToArray($object), $this->doConvertEtablissementToArray($etablissement));
    }

    protected function doConvertObjectToArray($object) {
        $parameters = array();

        if ($object->numero_contrat) {
        	$contrat = $object->numero_contrat;
        	if ($object->version) {
        		$contrat .= '-'.$object->version;
        	}
            $parameters["contrat"] = $contrat;
        }
        
        return $parameters;
    }

    protected function doConvertEtablissementToArray($etablissement) {
        if (!$etablissement) {

            return array("identifiant" => self::ETABLISSEMENT_IDENTIFIANT_ADMIN);
        }
        if (is_object($etablissement)) {
        	return array("identifiant" => $etablissement->identifiant);
        }
        return array("identifiant" => $etablissement);
    }
    

    
    public function checkSecurity($etablissement = null) {
    	if (!$etablissement) {
    		return;
    	}
    	$user = sfContext::getInstance()->getUser();
    	$compte = $user->getCompte();
    	if (!$user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $compte->type == 'CompteTiers') {
    		if (!$compte->hasEtablissement($etablissement->get('_id'))) {
    			return $this->redirect('@acces_interdit');
    		}
    	}
    }

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->getObject();
        }    

        return $this->vrac; 
    }

    public function getEtablissement() {
        return $this->etablissement;
    }
    
	public function redirect($url, $statusCode = 302)
	{
		if (is_object($statusCode) || is_array($statusCode))
		{
			$url = array_merge(array('sf_route' => $url), is_object($statusCode) ? array('sf_subject' => $statusCode) : $statusCode);
			$statusCode = func_num_args() >= 3 ? func_get_arg(2) : 302;
		}
		sfContext::getInstance()->getController()->redirect($url, 0, $statusCode);
		throw new sfStopException();
	}
    
}