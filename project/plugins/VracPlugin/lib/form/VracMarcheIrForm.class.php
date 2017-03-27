<?php
class VracMarcheIrForm extends VracMarcheForm 
{
	public static $mois = array('01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
	
    public function configure() {
        parent::configure();
		$this->setWidget('prix_total_unitaire', new sfWidgetFormInputFloat());
		$this->setValidator('prix_total_unitaire', new sfValidatorNumber(array('required' => false)));
		$this->getWidget('prix_total_unitaire')->setLabel('Prix unitaire total HT:');
		$this->getWidget('prix_total_unitaire')->setDefault($this->getObject()->getTotalUnitaire());
        unset($this['annexe']);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && isset($this['type_transaction'])) {
            unset($this['type_transaction']);
        }
        
        $annee = date('Y');
		$annees = array($annee => $annee, $annee+1 => $annee+1, $annee+2 => $annee+2);
        $this->setWidget('mercuriale_mois', new sfWidgetFormChoice(array('choices' => self::$mois)));
        $this->setValidator('mercuriale_mois', new sfValidatorChoice(array('required' => false, 'choices' => array_keys(self::$mois))));
        $this->setWidget('mercuriale_annee', new sfWidgetFormChoice(array('choices' => $annees)));
        $this->setValidator('mercuriale_annee', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($annees))));
        $this->setWidget('variation_hausse', new sfWidgetFormInputFloat());
        $this->setValidator('variation_hausse', new sfValidatorNumber(array('required' => false)));
        $this->setWidget('variation_baisse', new sfWidgetFormInputFloat());
        $this->setValidator('variation_baisse', new sfValidatorNumber(array('required' => false)));
        
        $this->validatorSchema->setPostValidator(new VracMarcheValidator());
    }
    protected function doUpdateObject($values) {
    	parent::doUpdateObject($values);
    	if (!sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		$this->getObject()->type_transaction = VracClient::TRANSACTION_DEFAUT;
    	}
    	if ($values['mercuriale_mois'] && $values['mercuriale_annee']) {
    		$this->getObject()->mercuriale = $values['mercuriale_annee'].'-'.$values['mercuriale_mois'];
    	}
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();
      
      if ($this->getObject()->mercuriale) {
      	$mercuriale = explode('-', $this->getObject()->mercuriale);
      	if (count($mercuriale) == 2) {
      		$this->setDefault('mercuriale_mois', $mercuriale[1]);
      		$this->setDefault('mercuriale_annee', $mercuriale[0]);
      	}
      }

    }
}