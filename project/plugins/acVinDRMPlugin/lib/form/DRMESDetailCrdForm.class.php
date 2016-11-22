<?php
class DRMESDetailCrdForm extends acCouchdbObjectForm {
	
	public function configure() {
		$this->setWidget('volume', new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
		$this->setValidator('volume', new sfValidatorNumber(array('required' => true)));
		
		$this->setWidget('mois', new sfWidgetFormChoice(array('choices' => $this->getMois())));
		$this->setValidator('mois', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getMois()))));
		
		$this->setWidget('annee', new sfWidgetFormChoice(array('choices' => $this->getAnnees())));
		$this->setValidator('annee', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAnnees()))));
		
		$this->widgetSchema->setNameFormat('drm_es_detail_crd[%s]');
		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
	}
	
	protected function doUpdateObject($values) {
		$this->getObject()->getParent()->getParent()->crd = $values['volume'];
		parent::doUpdateObject($values);
	}
	
	public function getMois() {
		$mois = array();
		for($i = 1; $i < 13; $i++) {
			$mois[$i] = sprintf("%02d", $i);
		}
		return $mois;
	}
	
	public function getAnnees() {
		$annees = array();
		for($i = $this->getObject()->getDocument()->getAnnee(); $i >= ($this->getObject()->getDocument()->getAnnee() - 10); $i--) {
			$annees[$i] = $i;
		}
		return $annees;
	}
}