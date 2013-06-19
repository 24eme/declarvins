<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompteOIOCModificationForm
 *
 * @author mathurin
 */
class CompteOIOCModificationForm extends CompteModificationForm {
   
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
         $choices = OIOCAllView::getInstance()->getAllOIOC();
         $this->setWidget('oioc',  new sfWidgetFormChoice(array('choices' => $choices)));
         $this->getWidget('oioc')->setLabel('OIOC*: ');
         $this->setValidator('oioc', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($choices))));
         $this->mergePostValidator(new ValidatorLoginCompte());
         
     }
     
     public function doUpdateObject($values) {
		parent::doUpdateObject($values);
        if (!$this->getObject()->isNew()) {
                        if($this->getObject()->get('_id')!='COMPTE-'.$this->getObject()->login)
                        {
                            throw new sfException("You can not modify this login.");
                        }
		} else {
			$this->getObject()->setMotDePasseSSHA($values['mdp1']);
		}
	}
}

?>
