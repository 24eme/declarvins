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
        parent::doUpdateObject($values);
        $configuration = ConfigurationClient::getCurrent();
        $configurationProduit = $configuration->getConfigurationProduit($this->getObject()->produit);
        $cvo = $configurationProduit->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, null, true);
        if (!$cvo) {
        	throw new sfException('Aucun résultat pour le produit '.$this->getObject()->produit);
        }
        if ($configurationProduit) {
        	$this->getObject()->setDetailProduit($configurationProduit);
        	$this->getObject()->produit_libelle = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles());
        }
        $this->getObject()->part_cvo = $cvo->taux;
        $this->getObject()->update();
    }
    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->getObject()->produit) {
        	$this->setDefault('produit', '/'.str_replace('/declaration/', 'declaration/', $this->getObject()->produit));
        	
        }   
    }
}