<?php

class DAIDSRoute extends sfObjectRoute implements InterfaceEtablissementRoute 
{
    protected $daids = null;
    
    protected function getObjectForParameters($parameters) 
    {
        if (!preg_match('/^[0-9]{4}-[0-9]{2}/', $parameters['periode_version'])) {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'periode_version', $parameters['periode_version']));
        }
        $this->daids = DAIDSClient::getInstance()->find('DAIDS-'.$parameters['identifiant'].'-'.$parameters['periode_version']);
        if(!$this->daids && isset($this->options['creation'])) {
            $this->daids = DAIDSClient::getInstance()->createDocByPeriode($parameters['identifiant'], $parameters['periode_version']);
        }
        if (!$this->daids) {
            throw new sfError404Exception(sprintf('No DAIDS found for this periode/version "%s".',  $parameters['periode_version']));
        }
		if (isset($this->options['must_be_valid']) && $this->options['must_be_valid'] === true && !$this->daids->isValidee()) {
			$this->redirect('daids_not_validated', array('identifiant' => $this->getEtablissement()->identifiant, 'periode_version' => $this->getDAIDS()->getPeriodeAndVersion()));
		}
		if (isset($this->options['must_be_not_valid']) && $this->options['must_be_not_valid'] === true && $this->daids->isValidee()) {
			$this->redirect('daids_validated', array('identifiant' => $this->getEtablissement()->identifiant, 'periode_version' => $this->getDAIDS()->getPeriodeAndVersion()));
		}
        return $this->daids;
    }

    protected function doConvertObjectToArray($object) 
    {  
        $parameters = array("identifiant" => $object->getIdentifiant(), "periode_version" => $object->getPeriodeAndVersion());
        return $parameters;
    }

    public function getDAIDS() 
    {
        if (!$this->daids) {
            $this->getObject();
        }
        return $this->daids;
    }

    public function getEtablissement() {
        return $this->getDAIDS()->getEtablissement();
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
    
    public function getDAIDSConfiguration() {
        return ConfigurationClient::getCurrent();
    }
}