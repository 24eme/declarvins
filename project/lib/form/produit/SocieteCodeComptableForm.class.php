<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SocieteCodeComptableForm extends acCouchdbObjectForm {

    public function configure()
    {
        $this->setWidget('code_comptable_client', new sfWidgetFormInputText());
        $this->setValidator('code_comptable_client', new sfValidatorRegex(array('required' => false, 'pattern' => '/^4110000C([0-9]+)$/')));
        $this->widgetSchema->setNameFormat('societe[%s]');
    }

}
