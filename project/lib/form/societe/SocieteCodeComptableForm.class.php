<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SocieteCodeComptableForm extends acCouchdbObjectForm {

    public function configure()
    {
        $this->setWidget('code_comptable_client', new sfWidgetFormInputText());
        $this->setValidator('code_comptable_client', new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setNameFormat('societe_code_comptable_client[%s]');
    }

}
