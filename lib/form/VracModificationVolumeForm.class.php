<?php

class VracModificationVolumeForm extends acCouchdbObjectForm
{
    public function configure()
    {
        $this->setWidgets(array(
           'volume_propose' => new sfWidgetFormInputFloat(),
        ));

        $this->setValidators(array(
           'volume_propose' => new sfValidatorNumber(array('required' => true, 'min' => $this->getObject()->volume_enleve)),
        ));

        $this->getValidator('volume_propose')->setMessage('required', "Le volume proposé est obligatoire");
        $this->getValidator('volume_propose')->setMessage('min', "Le volume proposé doit être supérieur au volume enlevé");

        $this->getWidget('volume_propose')->setLabel('Volume proposé');

        $this->validatorSchema->setPostValidator(new VracMarcheValidator());

        $this->widgetSchema->setNameFormat('modification_volume[%s]');
    }
}