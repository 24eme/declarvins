<?php
class DRMCertificationRoute extends DRMRoute 
{
	protected $certification;
    
    public function getDRMCertification() 
    {
        return $this->getObject();
    }
    
    public function getCertification()
    {
    	return $this->certification;
    }
    
    protected function getObjectForParameters($parameters) {
        parent::getObjectForParameters($parameters);

        if (!array_key_exists('certification', $parameters)) {
            return $this->getDRM()->declaration->certifications->getFirst();
        }
        $certifications = array_keys(ConfigurationClient::getCurrent()->getCertifications());
        if (in_array($parameters['certification'], $certifications)) {
        	$this->certification = $parameters['certification'];
            if (isset($this->options['add_noeud']) && $this->options['add_noeud'] === true) {
                return $this->getDRM()->declaration->certifications->add($parameters['certification']);
            } else {
                return $this->getDRM()->declaration->certifications->get($parameters['certification']);
            }
        }
        
        return null;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = parent::doConvertObjectToArray($object->getDocument());
        $parameters["certification"] = $object->getKey();
        
        return $parameters;
    }

    public function getChildrenNode() {

        return $this->genres;
    }

}