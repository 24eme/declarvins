<?php

class DouaneForm extends acCouchdbObjectForm 
{
    public function configure() 
    {
        $this->setWidgets(array(
                'nom' => new sfWidgetFormInputText(),
                'identifiant'  => new sfWidgetFormInputText(),
                'email'  => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
                'nom' => 'Nom*: ',
                'identifiant'  => 'Identifiant*: ',
                'email'  => 'Email*: '
        ));
        $this->setValidators(array(
                'email' => new sfValidatorEmail(array('required' => true)),
                'nom'  => new sfValidatorString(array('required' => true)),
                'identifiant'  => new sfValidatorString(array('required' => true))
        ));
        $this->widgetSchema->setNameFormat('douane[%s]');
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->isNew()) {
        	$this->getObject()->set('_id', $this->getObject()->generateId(strtoupper(KeyInflector::slugify($values['identifiant']))));
        	$this->getObject()->setStatut(Douane::STATUT_ACTIF);
        }
    }
}