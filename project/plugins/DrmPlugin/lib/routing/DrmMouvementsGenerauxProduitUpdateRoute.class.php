<?php

class DrmMouvementsGenerauxProduitUpdateRoute extends sfObjectRoute {
    
    protected function getObjectForParameters($parameters) {
        return $this->getDRM()->produits->get($parameters['certification'])->get($parameters['appellation'])->get($parameters['indice']);
    }

    protected function doConvertObjectToArray($object) {
        $parameters['certification'] = $object->getCertification()->getKey();
        $parameters['appellation'] = $object->getAppellation()->getKey();
        $parameters['indice'] = $object->getKey();
        
        return $parameters;
    }
    
    protected function getDRM() {
        return sfContext::getInstance()->getUser()->getDrm();
    }

}