<?php

class EtablissementRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    protected $etablissement = null;
    
    protected function getObjectForParameters($parameters = null) {
      $this->etablissement = EtablissementClient::getInstance()->find($parameters['identifiant']);
      $this->checkSecurity($this->etablissement);
      return $this->etablissement;
    }

    protected function doConvertObjectToArray($object = null) {
      $this->etablissement = $object;
      return array("identifiant" => $object->getIdentifiant());
    }

    public function getEtablissement() {
      if (!$this->etablissement) {
           $this->etablissement = $this->getObject();
      }
      return $this->etablissement;
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
