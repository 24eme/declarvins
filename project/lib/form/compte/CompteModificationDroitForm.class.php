<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompteModificationDroitForm
 *
 * @author mathurin
 */
class CompteModificationDroitForm extends CompteModificationForm {
   
    /* Construct the Form
     * 
     * 
     */
     public function configure() 
     {
         parent::configure();
         $choices = _CompteClient::getInstance()->getDroits();
         $this->setWidget('droits',  new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => true, 'multiple' => true)));
         $this->getWidget('droits')->setLabel('droits*: ');
         $this->setValidator('droits', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($choices),'multiple' => true)));
         
     }
     
     public function doUpdateObject($values) {
		parent::doUpdateObject($values);
		if (!$this->getObject()->isNew()) {
			$this->getObject()->setMotDePasseSSHA($values['mdp1']);
		}
	}
}

?>
