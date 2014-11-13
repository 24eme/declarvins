<?php
class DRMManquantsForm extends acCouchdbObjectForm
{
	public function configure()
  	{
      	$this->setWidgets(array(
        	'igp' => new WidgetFormInputCheckbox(),
      		'contrats' => new WidgetFormInputCheckbox(),
		));
		$this->widgetSchema->setLabels(array(
        	'igp' => 'Produit(s) IGP manquant(s)',
			'contrats' => 'Contrat(s) manquant(s)',
        ));
		$this->setValidators(array(
        	'igp' => new ValidatorBoolean(array('required' => false)),
			'contrats' => new ValidatorBoolean(array('required' => false)),
        ));
	    $this->widgetSchema->setNameFormat('drm_manquants[%s]');
  	}
}