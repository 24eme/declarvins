<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FactureSelectionForm extends baseForm {

  	public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  	{
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}

    public function configure()
    {
        $this->setWidget('identifiant', new WidgetFacture($this->getOptions()));

        $this->widgetSchema->setLabel('identifiant', 'Facture :');

        $this->setValidator('identifiant', new acValidatorCouchdbDocument(array('required' => true, 'type' => 'Etablissement', 'prefix' => 'ETABLISSEMENT-')));

        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'une facture est obligatoire');

        $this->widgetSchema->setNameFormat('facture_selection[%s]');
    }

    public function getEtablissement() {

        return $this->getValidator('identifiant')->getDocument();
    }

}
