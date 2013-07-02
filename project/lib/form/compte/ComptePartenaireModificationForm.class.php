<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComptePartenaireModificationForm
 *
 * @author mathurin
 */
class ComptePartenaireModificationForm extends CompteModificationForm {
   
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
         $accesChoices = _CompteClient::getInstance()->getAcces();
         $this->setWidget('acces',  new sfWidgetFormChoice(array('choices' => $accesChoices, 'expanded' => true, 'multiple' => true)));
         $this->getWidget('acces')->setLabel('Accès: ');
         $this->setValidator('acces', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($accesChoices),'multiple' => true)));
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
