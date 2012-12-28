<?php

class DAIDSEntrepotsForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->embedForm('entrepots', new DAIDSEntrepotsCollectionForm($this->getObject()->entrepots));
        $this->widgetSchema->setNameFormat('daids[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        
    }

}