<?php
class VracConditionForm extends VracForm 
{
   	public function configure()
    {
  		$this->setWidgets(array(
        	'has_transaction' => new WidgetFormInputCheckbox(),
        	'date_debut_retiraison' => new sfWidgetFormInputText(),
        	'date_limite_retiraison' => new sfWidgetFormInputText(),
        	'vin_livre' => new sfWidgetFormChoice(array('choices' => $this->getChoixVinLivre(),'expanded' => true)),
        	'reference_contrat_pluriannuel' => new sfWidgetFormInputText(),
        	'clause_reserve_retiraison' => new sfWidgetFormInputCheckbox(),
  		    'cas_particulier' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getCasParticulier(), 'renderer_options' => array('formatter' => array('VracSoussigneForm', 'casParticulierFormatter')))),
        	'export' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
    		'premiere_mise_en_marche' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'bailleur_metayer' => new WidgetFormInputCheckbox(),
        	'annexe' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'type_retiraison' => new sfWidgetFormChoice(array('choices' => $this->getChoixTypeRetiraison(),'expanded' => true)),
    	));
        $this->widgetSchema->setLabels(array(
        	'has_transaction' => 'je souhaite faire ma déclaration de transaction en même tant que mon contrat',
        	'date_debut_retiraison' => 'Date de début de retiraison*:',
        	'date_limite_retiraison' => 'Date limite de retiraison*:',
        	'vin_livre' => 'Le produit sera*:',
        	'reference_contrat_pluriannuel' => 'Référence du contrat pluriannuel adossé à ce contrat:',
        	'clause_reserve_retiraison' => 'Clause de reserve de propriété (si réserve, recours possible jusqu\'au paiement complet): ',
            'cas_particulier' => 'Condition particulière:',
        	'export' => 'Expédition export*:',
        	'premiere_mise_en_marche' => 'Première mise en marché:',
            'bailleur_metayer' => 'Entre bailleur et métayer:',
        	'annexe' => 'Présence d\'une annexe (cahier des charges techniques):',
        	'type_retiraison' => 'Type de retiraison/livraison:',
        ));
        $this->setValidators(array(
        	'has_transaction' => new ValidatorBoolean(),
        	'date_debut_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'date_limite_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true), array('invalid' => 'Format valide : dd/mm/aaaa')),
        	'vin_livre' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixVinLivre()))),
        	'reference_contrat_pluriannuel' => new sfValidatorString(array('required' => false)),
        	'clause_reserve_retiraison' => new ValidatorPass(),
            'cas_particulier' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCasParticulier()))),
        	'export' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'premiere_mise_en_marche' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
            'bailleur_metayer' => new ValidatorBoolean(array('required' => false)),
        	'annexe' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'type_retiraison' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixTypeRetiraison()))),
        ));
  		
  		
		$this->setWidget('volume_propose', new sfWidgetFormInputHidden());
		$this->setValidator('volume_propose', new ValidatorPass());
  		$this->validatorSchema->setPostValidator(new VracConditionValidator());
  		$this->widgetSchema->setNameFormat('vrac_condition[%s]');
    }

    protected function doUpdateObject($values) {
      $this->getObject()->cas_particulier_libelle = $this->getConfiguration()->formatCasParticulierLibelle(array($this->getObject()->cas_particulier));
      parent::doUpdateObject($values); 
        if (!$this->getObject()->annexe) {
        	$this->getObject()->annexe = 0;
        }
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      if (is_null($this->getObject()->vin_livre)) {
        $this->setDefault('vin_livre', VracClient::STATUS_VIN_RETIRE);
      }  
      $this->setDefault('cas_particulier', (($this->getObject()->cas_particulier) ? $this->getObject()->cas_particulier : null));
      if (is_null($this->getObject()->export)) {
          $this->setDefault('export', 0);
      }
      if (is_null($this->getObject()->annexe)) {
        $this->setDefault('annexe', 0);
      }   
    }

    public function conditionneIVSE() {
      return false;
    }
}