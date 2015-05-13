<?php
class AlerteAdminForm extends acCouchdbObjectForm
{
	public function configure() 
  	{
		$this->setWidgets(array(
			'statut' => new sfWidgetFormChoice(array('choices' => Alerte::getStatuts())),
			'commentaire' => new sfWidgetFormTextarea()
		));
		$this->setValidators(array(
			'statut' => new sfValidatorChoice(array('choices' => array_keys(Alerte::getStatuts()))),
			'commentaire' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setLabels(array(
			'statut' => 'Statut: ',
			'commentaire' => 'Commentaire: '
		));
		$this->widgetSchema->setNameFormat('alerte[%s]');
  	}
}