<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FactureSelectionForm extends baseForm {

    public $interpro = null;

  	public function __construct($interpro, $defaults = array(), $options = array(), $CSRFSecret = null)
  	{
        $this->interpro = $interpro;
    	parent::__construct($defaults, $options, $CSRFSecret);
  	}

    public function configure()
    {
        $this->setWidget('identifiant', new WidgetFacture($this->interpro, $this->getOptions()));

        $this->widgetSchema->setLabel('identifiant', 'Factures non payées :');

        $this->setValidator('identifiant', new acValidatorCouchdbDocument(array('required' => true, 'type' => 'Etablissement', 'prefix' => 'ETABLISSEMENT-')));

        $this->validatorSchema['identifiant']->setMessage('required', 'Le choix d\'une facture est obligatoire');

        $this->widgetSchema->setNameFormat('facture_selection[%s]');
    }

    public function getEtablissement() {

        return $this->getValidator('identifiant')->getDocument();
    }

}
