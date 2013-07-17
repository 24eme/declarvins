<?php

class UtilisateursDocument implements IUtilisateursDocument
{
    protected $document;

    public function __construct(acCouchdbDocument $document)
    {
        $this->document = $document;
    }

    public function addEdition($id_user, $date) {
        $this->document->add('utilisateurs')->add('edition')->add($id_user, $date);
    }

    public function addValidation($id_user, $date) {
        $this->document->add('utilisateurs')->add('validation')->add($id_user, $date);
    }

    public function getEdition() {
        if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('edition'))) 
            return $this->document->get('utilisateur')->get('edition');
        return null;
    }

    public function getValidation() {    
        if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('validation'))) 
            return $this->document->get('utilisateur')->get('validation');
        return null;
    }
    
    public function getLastEdition() {
        if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('edition'))) 
            return $this->document->get('utilisateur')->get('edition');
        return null;
    }

    public function getLastValidation() {        
        if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('validation'))) 
            return $this->document->get('utilisateur')->get('validation');
        return null;
    }
}
