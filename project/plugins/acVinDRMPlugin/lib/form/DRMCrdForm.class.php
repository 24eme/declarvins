<?php

class DRMCrdForm extends acCouchdbObjectForm 
{

    public function configure() 
    {
        $this->widgetSchema->setNameFormat('drm_crd[%s]');
    }

    
}