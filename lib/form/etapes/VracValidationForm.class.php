<?php
class VracValidationForm extends VracForm 
{
	public function configure()
    {
    	parent::configure();
		if ($this->user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
			$fields = array('valide','commentaires', 'date_signature');
		} else {
			$fields = array('valide','commentaires');
		}
		$this->useFields($fields);
        $this->widgetSchema->setNameFormat('vrac_validation[%s]');
    }

    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (isset($values['date_signature']) && $values['date_signature']) {
    		$date = new DateTime($values['date_signature']);
    		$this->getObject()->getDocument()->date_signature = $date->format('c');
    	}
        $this->getObject()->getDocument()->validate($this->user);
    }
}