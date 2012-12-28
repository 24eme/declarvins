<?php

class DAIDSEntrepotsCollectionForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	foreach ($this->getObject() as $entrepot_key => $entrepot) {
    		$this->embedForm($entrepot_key, new DAIDSEntrepotForm($entrepot));
    	}
        $this->widgetSchema->setNameFormat('entrepots[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        
    }

}