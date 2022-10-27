<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SocieteCodeComptableForm extends acCouchdbObjectForm {

    public $interpro;

    public function __construct(acCouchdbJson $object, $interpro, $options = array(), $CSRFSecret = null) {
        $this->interpro = $interpro;
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure()
    {
        $this->setWidget($this->interpro, new sfWidgetFormInputText());
        $this->setValidator($this->interpro, new sfValidatorString(array('required' => true)));
        $this->widgetSchema->setNameFormat('societe_codes_comptables_client[%s]');
    }

}
