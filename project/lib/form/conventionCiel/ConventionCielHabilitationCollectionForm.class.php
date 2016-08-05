<?php
class ConventionCielHabilitationCollectionForm extends acCouchdbForm
{
  public function configure()
  {    
	    for ($i=0; $i<$this->getOption('nbHabilitation', 1); $i++) {
	    	if ($this->getObject()->habilitations->exist($i)) {
	    		$this->embedForm ($i, new ConventionCielHabilitationForm($this->getObject()->habilitations->get($i)));
	    	} else {
	    		$this->embedForm ($i, new ConventionCielHabilitationForm($this->getObject()->habilitations->add()));
	    	}
	    }
  }
  
  public function getObject() {
  	return $this->getDocument();
  }
}