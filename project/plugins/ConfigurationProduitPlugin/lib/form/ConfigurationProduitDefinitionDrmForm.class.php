<?php
class ConfigurationProduitDefinitionDrmForm extends sfForm 
{
    public function configure() { 
    	
    	$this->setWidget('entree_repli',  new WidgetFormInputCheckbox());
        $this->setValidator('entree_repli', new ValidatorBoolean(array('required' => false)));
        
        $this->setWidget('entree_declassement',  new WidgetFormInputCheckbox());
        $this->setValidator('entree_declassement', new ValidatorBoolean(array('required' => false)));
    	
    	$this->setWidget('sortie_repli',  new WidgetFormInputCheckbox());
        $this->setValidator('sortie_repli', new ValidatorBoolean(array('required' => false)));
        
        $this->setWidget('sortie_declassement',  new WidgetFormInputCheckbox());
        $this->setValidator('sortie_declassement', new ValidatorBoolean(array('required' => false)));
        
    	if ($definitionDrm = $this->getOption('definition_drm')) {
	    	$this->setDefault('entree_repli', $definitionDrm->entree->repli);
	    	$this->setDefault('entree_declassement', $definitionDrm->entree->declassement);
	    	$this->setDefault('sortie_repli', $definitionDrm->sortie->repli);
	    	$this->setDefault('sortie_declassement', $definitionDrm->sortie->declassement);
		}
        
    	$this->widgetSchema->setNameFormat('[%s]');
    }
}