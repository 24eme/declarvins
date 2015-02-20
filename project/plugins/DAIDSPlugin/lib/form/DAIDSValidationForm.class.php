<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class DAIDSValidationForm
 * @author mathurin
 */
class DAIDSValidationForm extends acCouchdbObjectForm  {
      public function configure() {
        $this->setWidget('commentaires', new sfWidgetFormTextarea());
      	$this->getWidget('commentaires')->setLabel("Commentaires");
      	$this->setValidator('commentaires', new sfValidatorString(array('required' => false)));
	    
	    $this->widgetSchema->setNameFormat('drm_validation[%s]');
      } 
    
}
