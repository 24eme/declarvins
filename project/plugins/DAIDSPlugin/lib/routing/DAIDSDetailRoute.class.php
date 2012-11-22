<?php

class DAIDSDetailRoute extends DAIDSLieuRoute 
{

    public function getDAIDSDetail() 
    {
        return $this->getObject();
    }
    
    public function getDAIDSLieu() 
    {
        return $this->getDAIDSDetail()->getLieu();
    }
    
    protected function getObjectForParameters($parameters) 
    {
        $config_lieu = parent::getObjectForParameters($parameters);
        
        $daids_detail = $this->getDAIDS()->get($config_lieu->getHash())
                                     ->couleurs->add($parameters['couleur'])
                                     ->cepages->add($parameters['cepage'])
                                     ->details->get($parameters['detail']);

        return $daids_detail;
    }

    protected function doConvertObjectToArray($object) 
    {
        $parameters = parent::doConvertObjectToArray($object->getLieu());
        $parameters['couleur'] = $object->getCouleur()->getKey();
        $parameters['cepage'] = $object->getCepage()->getKey();
        $parameters['detail'] = $object->getKey();
        
        return $parameters;
    }

}