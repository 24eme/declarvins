<?php
class ProduitDroitForm extends sfForm {

    public function configure() {
    	$this->setWidgets(array(
			'date' => new sfWidgetFormInputText(),
			'code' => new sfWidgetFormInputText(),
			'libelle' => new sfWidgetFormInputText(),
			'taux' => new sfWidgetFormInputFloat()  		
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date: ',
			'code' => 'Code: ',
			'libelle' => 'Libelle: ',
			'taux' => 'Taux: '
		));
		$this->setValidators(array(
			'date' => new sfValidatorString(array('required' => false)),
			'code' => new sfValidatorString(array('required' => false)),
			'libelle' => new sfValidatorString(array('required' => false)),
			'taux' => new sfValidatorNumber(array('required' => false))
		));
		if ($droit = $this->getOption('droit')) {
			$this->setDefaults(array(
	    		'date' => $droit->date,
	    		'code' => $droit->code,
	    		'libelle' => $droit->libelle,
	    		'taux' => $droit->taux
	    	));
		}
        $this->widgetSchema->setNameFormat('produit_droit[%s]');
    }
}