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
        $result = ConfigurationClient::getInstance()->getDroitsByHashAndTypeAndPeriode('/'.$this->getObject()->produit, DRMDroits::DROIT_CVO);
        if (!$result) {
        	throw new sfException('Aucun résultat pour le produit '.$this->getObject()->produit);
        }
        $configuration = ConfigurationClient::getCurrent();
        if ($configuration->exist($this->getObject()->produit)) {
        	$produit = $configuration->get($this->getObject()->produit);
        	$this->getObject()->setDetailProduit($produit);
        	$this->getObject()->produit_libelle = $this->getObject()->getLibelleProduit();
        }
        $this->getObject()->part_cvo = $result->taux;
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