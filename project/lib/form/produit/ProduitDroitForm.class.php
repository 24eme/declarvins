<?php
class ProduitDroitForm extends acCouchdbFormDocumentJson {

    public function configure() {
    	$years = range(date('Y') - 10, date('Y') + 10);
    	$this->setWidgets(array(
			'date' => new sfWidgetFormDate(array('format' => '%day% / %month% / %year%', 'years' => array_combine($years, $years))),
			'code' => new sfWidgetFormInputText(),
			'taux' => new sfWidgetFormInputFloat()  		
    	));
		$this->widgetSchema->setLabels(array(
			'date' => 'Date: ',
			'code' => 'Code*: ',
			'taux' => 'Taux*: '
		));
		$this->setValidators(array(
			'date' => new sfValidatorDate(array('required' => true), array('required' => 'Champ obligatoire')),
			'code' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'taux' => new sfValidatorNumber(array('required' => true), array('required' => 'Champ obligatoire'))
		));
        $this->widgetSchema->setNameFormat('produit_droit[%s]');
    }
}