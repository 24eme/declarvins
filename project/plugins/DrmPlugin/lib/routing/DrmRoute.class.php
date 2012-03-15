<?php

class DrmRoute extends sfObjectRoute {

    protected $drm = null;
    
    protected function getObjectForParameters($parameters) {

        if (preg_match('/^[0-9]{4}-[0-9]{2}$/', $parameters['campagne_rectificative'])) {
            $campagne = $parameters['campagne_rectificative'];
            $rectificative = null;
        } elseif(preg_match('/^([0-9]{4}-[0-9]{2})-R([0-9]{2})$/', $parameters['campagne_rectificative'], $matches)) {
            $campagne = $matches[1];
            $rectificative = $matches[2];
        } else {
            throw new InvalidArgumentException(sprintf('The "%s" route has an invalid parameter "%s" value "%s".', $this->pattern, 'campagne_rectificative', $parameters['campagne_rectificative']));
        }

        $this->drm = DRMClient::getInstance()->findByIdentifiantCampagneAndRectificative(sfContext::getInstance()->getUser()->getTiers()->identifiant, 
                                                                                         $campagne, 
                                                                                         $rectificative);

        if (!$this->drm) {
            throw new sfError404Exception(sprintf('No DRM found for this campagne-rectificative "%s".',  $parameters['campagne_rectificative']));
        }

        return $this->drm;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("campagne_rectificative" => $object->getCampagneAndRectificative());
        
        return $parameters;
    }
    
    public function getDRMConfiguration() {
        return ConfigurationClient::getCurrent();
    }

    public function getDrm() {
        if (!$this->drm) {
            $this->getObject();
        }

        return $this->drm;
    }

}