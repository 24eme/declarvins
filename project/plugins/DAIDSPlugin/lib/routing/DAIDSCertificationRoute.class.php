<?php

class DAIDSCertificationRoute extends DAIDSRoute 
{
	protected $certification;
    
    public function getDAIDSCertification() 
    {
        return $this->getObject();
    }
    
    public function getCertification()
    {
    	return $this->certification;
    }
    
    protected function getObjectForParameters($parameters) 
    {
        parent::getObjectForParameters($parameters);
        if (!array_key_exists('certification', $parameters)) {
            return $this->getDAIDS()->declaration->certifications->getFirst();
        }
        $certifications = ConfigurationClient::getCurrent()->getCertifications();
        if (in_array($parameters['certification'], $certifications)) {
        	$this->certification = $parameters['certification'];
            if (isset($this->options['add_noeud']) && $this->options['add_noeud'] === true) {
                return $this->getDAIDS()->declaration->certifications->add($parameters['certification']);
            } else {
                return $this->getDAIDS()->declaration->certifications->get($parameters['certification']);
            }
        }
        return null;
    }

    protected function doConvertObjectToArray($object) 
    {
        $parameters = parent::doConvertObjectToArray($object->getDocument());
        $parameters["certification"] = $object->getKey();
        return $parameters;
    }

    public function getChildrenNode() 
    {
        return $this->genres;
    }

}