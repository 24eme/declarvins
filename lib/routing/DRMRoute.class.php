<?php

class DRMRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    protected $drm = null;
    
    protected function getObjectForParameters($parameters) {

        if (preg_match('/^[0-9]{4}-[0-9]{2}$/', $parameters['periode_version'])) {
            $campagne = $parameters['periode_version'];
            $rectificative = null;
        } elseif(preg_match('/^([0-9]{4}-[0-9]{2})-R([0-9]{2})$/', $parameters['periode_version'], $matches)) {
            $campagne = $matches[1];
            $rectificative = $matches[2];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'periode_version', $parameters['periode_version']));
        }

        $this->drm = DRMClient::getInstance()->find('DRM-'.$parameters['identifiant'].'-'.$parameters['periode_version']);

        if(!$this->drm && isset($this->options['creation'])) {
            $this->drm = DRMClient::getInstance()->createDocByPeriode($parameters['identifiant'], $parameters['periode_version']);
        }

        if (!$this->drm) {
            throw new sfError404Exception(sprintf('No DRM found for this campagne-rectificative "%s".',  $parameters['periode_version']));
        }
		if (isset($this->options['must_be_valid']) && $this->options['must_be_valid'] === true && !$this->drm->isValidee()) {
			//throw new sfError404Exception('DRM must be validated');
			$this->redirect('drm_not_validated', array('identifiant' => $this->getEtablissement()->identifiant, 'periode_version' => $this->getDRM()->getPeriodeAndVersion()));
		}
		if (isset($this->options['must_be_not_valid']) && $this->options['must_be_not_valid'] === true && $this->drm->isValidee()) {
			//throw new sfError404Exception('DRM must not be validated');
			$this->redirect('drm_validated', array('identifiant' => $this->getEtablissement()->identifiant, 'periode_version' => $this->getDRM()->getPeriodeAndVersion()));
		}
        return $this->drm;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("identifiant" => $object->getIdentifiant(), "periode_version" => $object->getPeriodeAndVersion());
        return $parameters;
    }
    
    public function getDRMConfiguration() {
        return ConfigurationClient::getCurrent();
    }

    public function getDRM() {
        if (!$this->drm) {
            $this->getObject();
        }

        return $this->drm;
    }

    public function getEtablissement() {

        return $this->getDRM()->getEtablissement();
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