<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracMarcheForm extends acCouchdbObjectForm {
   
    private $types_transaction = array(VracClient::TYPE_TRANSACTION_RAISINS => 'Raisins',
                                       VracClient::TYPE_TRANSACTION_MOUTS => 'Moûts',
                                       VracClient::TYPE_TRANSACTION_VIN_VRAC => 'Vin en vrac',
                                       VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE => 'Vin en bouteille');
    protected $config;
    
	public function __construct(Configuration $config, acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->config = $config;
        parent::__construct($object, $options, $CSRFSecret);
    }

    
    public function configure()
    {
        $originalArray = array('1' => 'Oui', '0' =>'Non'); 
        
        $this->setWidget('original', new sfWidgetFormChoice(array('choices' => $originalArray,'expanded' => true)));
        $this->setWidget('type_transaction', new sfWidgetFormChoice(array('choices' => $this->types_transaction,'expanded' => true)));        
        $this->setWidget('produit', new sfWidgetFormChoice(array('choices' => $this->getProduits()), array('class' => 'autocomplete')));
        $this->setWidget('millesime', new sfWidgetFormChoice(array('choices' => $this->getMillesimes(),'multiple' => false, 'expanded' => false)));      
        $this->setWidget('domaine', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('label', new sfWidgetFormChoice(array('choices' => $this->getLabels(),'multiple' => true, 'expanded' => true)));
        $this->setWidget('raisin_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('jus_quantite', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_quantite', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('bouteilles_contenance', new sfWidgetFormChoice(array('choices' => $this->getTabContenances())));
        $this->setWidget('prix_unitaire', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        
        $this->widgetSchema->setLabels(array(
            'original' => "En attente de l'original ?",
            'type_transaction' => 'Type de transaction',
            'produit' => 'produit',
            'millesime' => 'Millésime',
            'domaine' => 'Nom du domaine',
            'label' => 'label',
            'bouteilles_quantite' => 'Nombre de bouteilles',
            'raisin_quantite' => 'Nombre de raisins',
            'jus_quantite' => 'Volume livré',
            'bouteilles_contenance' => 'Contenance',
            'prix_unitaire' => 'Prix'
        ));
        
        $this->setValidators(array(
            'original' => new sfValidatorInteger(array('required' => true)),
            'type_transaction' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->types_transaction))),
            'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getProduits()))),
            'millesime' => new sfValidatorInteger(array('required' => true)),
            'domaine' => new sfValidatorString(array('required' => false)),
            'label' => new sfValidatorChoice(array('required' => false,'multiple' => true, 'choices' => array_keys($this->getLabels()))),
            'bouteilles_quantite' =>  new sfValidatorInteger(array('required' => false)),
            'raisin_quantite' =>  new sfValidatorNumber(array('required' => false)),
            'jus_quantite' =>  new sfValidatorNumber(array('required' => false)), 
            'bouteilles_contenance' => new sfValidatorString(array('required' => true)),
            'prix_unitaire' => new sfValidatorNumber(array('required' => true))
                ));
        $this->widgetSchema->setNameFormat('vrac[%s]');
        
    }
    private function getVendeur() 
    {
    	return $this->getObject()->getVendeurObject();
    }
    private function getMillesimes()
    {
    	return $this->config->getMillesimes();
    }
    
    protected function getProduits()
    {
    	return array(
    		array(''=>''),
    		$this->config->formatProduits()
    	);
    }
    
    private function getContenances()
    {
    	return $this->config->getContenances()->toArray();
    }
    
    private function getTabContenances()
    {
    	$contenances = $this->getContenances();
    	$result = array();
    	foreach ($contenances as $contenanceKey => $contenanceValue) {
    		$result[$contenanceKey] = $contenanceKey;
    	}
    	return $result;
    }
    
    private function getContenanceValue($key)
    {
    	$contenances = $this->getContenances();
    	return (isset($contenances[$key]))? $contenances[$key] : null;
    }
    
    private function getLabels()
    {
    	return $this->config->getLabels()->toArray();
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
        $this->getObject()->set('bouteilles_contenance', $this->getContenanceValue($values['bouteilles_contenance']));
        $this->getObject()->update();
    }
    
}
?>
