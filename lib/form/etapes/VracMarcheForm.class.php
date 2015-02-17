<?php
class VracMarcheForm extends VracForm 
{
   	public function configure()
    {   		
    		$this->setWidgets(array(
        	'has_transaction' => new sfWidgetFormInputCheckbox(),
        	'has_cotisation_cvo' => new sfWidgetFormInputHidden(array('default' => 1)),
        	'type_transaction' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesTransaction())),
        	'labels' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getLabels())),
        	'mentions' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getMentions(), 'multiple' => true)),
        	'volume_propose' => new sfWidgetFormInputFloat(),
        	'annexe' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
        	'prix_unitaire' => new sfWidgetFormInputFloat(),
        	'type_prix' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getTypesPrix())),
        	'determination_prix' => new sfWidgetFormTextarea(),
        	'prix_total' => new sfWidgetFormInputHidden(),
	        'part_cvo' => new sfWidgetFormInputHidden(),
	        'repartition_cvo_acheteur' => new sfWidgetFormInputHidden(array('default' => ConfigurationVrac::REPARTITION_CVO_ACHETEUR)),
        	'export' => new sfWidgetFormChoice(array('choices' => $this->getChoixOuiNon(),'expanded' => true)),
	        'millesime' => new sfWidgetFormInputText()
    	));
        $this->widgetSchema->setLabels(array(
        	'has_transaction' => 'Je souhaite associer une déclaration de transaction',
        	'has_cotisation_cvo' => 'Cvo',
        	'type_transaction' => 'Type de transaction:',
        	'labels' => 'Labels:',
        	'mentions' => 'Mentions:',
        	'volume_propose' => 'Volume total proposé*:',
        	'annexe' => 'Présence d\'une annexe (cahier des charges techniques):',
        	'prix_unitaire' => 'Prix unitaire net HT hors cotisation*:',
        	'type_prix' => 'Type de prix*:',
        	'determination_prix' => 'Mode de détermination du prix définitif:',
        	'prix_total' => 'Prix total HT:',
        	'part_cvo' => 'Part CVO:',
        	'repartition_cvo_acheteur' => 'Repartition CVO acheteur:',
        	'export' => 'Expédition export*:',
	        'millesime' => 'Millesime:'
        ));
        $min = ($this->getObject()->volume_enleve)? $this->getObject()->volume_enleve : 0;
        $minErreur = ($min > 1)? $min.' hl ont déjà été enlevés pour ce contrat' : $min.' hl a déjà été enlevé pour ce contrat';
        $this->setValidators(array(
        	'has_transaction' => new ValidatorPass(),
        	'has_cotisation_cvo' => new ValidatorPass(),
        	'type_transaction' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypesTransaction()))),
        	'labels' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getLabels()))),
        	'mentions' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMentions()), 'multiple' => true)),
        	'volume_propose' => new sfValidatorNumber(array('required' => true, 'min' => $min), array('min' => $minErreur)),
        	'annexe' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getChoixOuiNon()))),
        	'prix_unitaire' => new sfValidatorNumber(array('required' => true)),
        	'type_prix' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesPrix()))),
        	'determination_prix' => new sfValidatorString(array('required' => false)),
        	'prix_total' => new sfValidatorNumber(array('required' => false)),
	        'part_cvo' => new sfValidatorNumber(array('required' => false)),
	        'repartition_cvo_acheteur' => new sfValidatorNumber(array('required' => false)),
        	'export' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getChoixOuiNon()))),
	        'millesime' => new sfValidatorString(array('required' => false))
         ));
    		
    		
    		$this->setWidget('non_millesime', new sfWidgetFormInputCheckbox());
    		$this->widgetSchema->setLabel('non_millesime', '&nbsp;');
    		$this->setValidator('non_millesime', new ValidatorPass());
    		
		    $this->getObject()->has_cotisation_cvo = 1;
  		    $this->validatorSchema->setPostValidator(new VracMarcheValidator());
    		$this->widgetSchema->setNameFormat('vrac_marche[%s]');
    		
    	if ($this->getObject()->hasVersion() && $this->getObject()->volume_enleve > 0) {
		      	$this->setWidget('millesime', new sfWidgetFormInputHidden());
            	unset($this['non_millesime']);
		      }

        if (count($this->getTypesTransaction()) < 2) {
            unset($this['type_transaction']);
        }
    }
    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $types_transaction = $this->getTypesTransaction();
        if (count($types_transaction) == 1) {
            foreach($types_transaction as $key => $value) {
                $this->getObject()->type_transaction = $key;
            }
        }

        if (!in_array($this->getObject()->type_prix, $this->getTypePrixNeedDetermination())) {
          $this->getObject()->determination_prix = null;
        }
        if (!$this->getObject()->annexe) {
        	$this->getObject()->annexe = 0;
        }
        $this->getObject()->labels_libelle = $this->getConfiguration()->formatLabelsLibelle(array($this->getObject()->labels));
        $this->getObject()->mentions_libelle = $this->getConfiguration()->formatMentionsLibelle($this->getObject()->mentions);
        $this->getObject()->type_transaction_libelle = $this->getConfiguration()->formatTypesTransactionLibelle(array($this->getObject()->type_transaction));
        $this->getObject()->update();
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      if (is_null($this->getObject()->type_transaction)) {
        $this->setDefault('type_transaction', VracClient::TRANSACTION_DEFAUT);
      }      
      if (is_null($this->getObject()->type_prix)) {
        $this->setDefault('type_prix', VracClient::PRIX_DEFAUT);
      }  
      if (is_null($this->getObject()->export)) {
        $this->setDefault('export', 0);
      }   
      if (is_null($this->getObject()->annexe)) {
        $this->setDefault('annexe', 0);
      }   
      if (is_null($this->getObject()->labels)) {
        $this->setDefault('labels', VracClient::LABEL_DEFAUT);
      }    
      if (!(count($this->getObject()->mentions->toArray()) > 0)) {
        $this->setDefault('mentions', '');
      }  
      	if (!$this->getObject()->millesime && $this->getObject()->volume_propose) {
        		$this->setDefault('non_millesime', true);
        	}
    }

    public function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }
}