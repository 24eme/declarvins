<?php
class VracProduitForm extends VracForm 
{
   	public function configure()
    {
    		$produits = $this->getProduits();
	    	$this->setWidgets(array(
	        	'produit' => new sfWidgetFormChoice(array('choices' => $produits), array('class' => 'autocomplete'))
	    	));
	        $this->widgetSchema->setLabels(array(
	        	'produit' => 'Produit*:'
	        ));
	        $this->setValidators(array(
	        	'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($produits)))
	        ));
    		
    		
    
	        
		    if ($this->getObject()->hasVersion() && $this->getObject()->volume_enleve > 0) {
		      	$this->setWidget('produit', new sfWidgetFormInputHidden());
		      }
    		
  		    $this->validatorSchema->setPostValidator(new VracProduitValidator());
    		$this->widgetSchema->setNameFormat('vrac_produit[%s]');
    }
    protected function doUpdateObject($values) {
    	$persit = null;
    	if (preg_match('/'.str_replace('/', '\/', $values['produit']).'/', $this->getObject()->produit)) {
    		$persit = $this->getObject()->produit;
    	}
        parent::doUpdateObject($values);
        $this->getObject()->produit = ($persit)? $persit : $values['produit'].'/cepages/'.ConfigurationProduit::DEFAULT_KEY;
        $configuration = ConfigurationClient::getCurrent();
        $configurationProduit = $configuration->getConfigurationProduit($persit);
        if ($configurationProduit) {
        	$this->getObject()->setDetailProduit($configurationProduit);
        	$this->getObject()->produit_libelle = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles());
        	$cvo = $configurationProduit->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, null, true);
	        if ($cvo) {
	        	$this->getObject()->part_cvo = $cvo->taux;
	        }
        }
        $this->getObject()->update();
    }
    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->getObject()->produit) {
        	preg_match('/([0-9a-zA-Z\/]+)\/cepages\/[0-9a-zA-Z\/]+/', $this->getObject()->produit, $matches);
        	$this->setDefault('produit', '/'.str_replace('/declaration/', 'declaration/', $matches[1]));
        	
        }   
    }
}