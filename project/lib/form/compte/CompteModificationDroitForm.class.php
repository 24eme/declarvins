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
         
         if($this->getObject()->isNew()) {
            $this->setWidget('login', new sfWidgetFormInputText());
            $this->getWidget('login')->setLabel('Login*: ');
            $this->setValidator('login', new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')));
         }
         
         $choices = _CompteClient::getInstance()->getDroits();
         $this->setWidget('droits',  new sfWidgetFormChoice(array('choices' => $choices, 'expanded' => true, 'multiple' => true)));
         $this->getWidget('droits')->setLabel('droits*: ');
         $this->setValidator('droits', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($choices),'multiple' => true)));
         
     }
     
     public function doUpdateObject($values) {
		parent::doUpdateObject($values);
		
        if (!$this->getObject()->isNew()) {
                        if($this->getObject()->get('_id')!='COMPTE-'.$this->getObject()->login)
                        {
                            throw new sfException("You can not modify this login.");
                        }
		}

        if($values['mdp1']) {
            $this->getObject()->setMotDePasseSSHA($values['mdp1']);
        }
	}
}

?>
