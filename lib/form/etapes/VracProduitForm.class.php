<?php
class VracProduitForm extends VracForm 
{
   	public function configure()
    {
    		parent::configure();
    		$this->useFields(array(
               'produit',
    		   'millesime'
    		));
    		$this->setWidget('non_millesime', new sfWidgetFormInputCheckbox());
    		$this->widgetSchema->setLabel('non_millesime', '&nbsp;');
    		$this->setValidator('non_millesime', new sfValidatorPass());
    		
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
        	if (!$this->getObject()->millesime) {
        		$this->setDefault('non_millesime', true);
        	}
        }   
    }
}