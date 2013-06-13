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
  		    $this->validatorSchema->setPostValidator(new VracProduitValidator());
    		$this->widgetSchema->setNameFormat('vrac_produit[%s]');
    }
    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $result = ConfigurationClient::getInstance()->findDroitsByHashAndType('/'.$this->getObject()->produit, DRMDroits::DROIT_CVO)->rows;
        if (count($result) == 0) {
        	throw new sfException('Aucun résultat pour le produit '.$this->getObject()->produit);
        }
        $configuration = ConfigurationClient::getCurrent();
        if ($configuration->exist($this->getObject()->produit)) {
        	$produit = $configuration->get($this->getObject()->produit);
        	$this->getObject()->setDetailProduit($produit);
        	$this->getObject()->produit_libelle = $this->getObject()->getLibelleProduit();
        }
        $result = $result[0];
        $droits = $result->value;
        $this->getObject()->part_cvo = $droits->taux;
        $this->getObject()->update();
    }
}