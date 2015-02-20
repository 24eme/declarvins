<?php

class DAIDSEntrepotsForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->embedForm('entrepots', new DAIDSEntrepotsCollectionForm($this->getObject()->entrepots));
        $this->widgetSchema->setNameFormat('daids[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        
    }
    
    public function save($con = null) {
    	$values = $this->getValues();
    	$this->getObject()->setEntrepotsInformations($values['entrepots']);
    	$this->getObject()->save();
    }
    
    

}