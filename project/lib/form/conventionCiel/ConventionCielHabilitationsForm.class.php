<?php
class ConventionCielHabilitationsForm extends acCouchdbObjectForm {

    public function configure() {
		
    	$formHabilitations = new ConventionCielHabilitationCollectionForm($this->getObject(), array(), array(
    			'nbHabilitation'    => $this->getOption('nbHabilitation', 1)
    	));
  		$this->embedForm('habilitations', $formHabilitations);

  		$this->mergePostValidator(new ValidatorConventionCielHabilitations());

        $this->widgetSchema->setNameFormat('convention_ciel[%s]');
        

    }

}