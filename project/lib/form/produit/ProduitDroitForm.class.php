<?php
class ProduitDroitForm extends sfForm {

    public function configure() {
    	$years = range(date('Y') - 10, date('Y') + 10);
    	$this->setWidgets(array(
			'date' => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => array_combine($years, $years))),
			'code' => new sfWidgetFormInputText(),
			'taux' => new sfWidgetFormInputFloat()  		
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date: ',
			'code' => 'Code: ',
			'taux' => 'Taux: '
		));
		$this->setValidators(array(
			'date' => new sfValidatorDate(array('required' => false)),
			'code' => new sfValidatorString(array('required' => false)),
			'taux' => new sfValidatorNumber(array('required' => false))
		));
		if ($droit = $this->getOption('droit')) {
			$this->setDefaults(array(
	    		'date' => $droit->date,
	    		'code' => $droit->code,
	    		'taux' => $droit->taux
	    	));
		}
        $this->widgetSchema->setNameFormat('produit_droit[%s]');
    }
}