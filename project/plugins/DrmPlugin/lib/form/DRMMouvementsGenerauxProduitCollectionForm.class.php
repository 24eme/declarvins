<?php

class DRMMouvementsGenerauxProduitCollectionForm extends acCouchdbFormDocumentJson {
	

	public function configure()
	{
		foreach ($this->getObject() as $key => $produit) {
			$this->embedForm ($key, new DRMMouvementsGenerauxProduitModificationForm($produit));
		}
		$this->widgetSchema->setNameFormat('drm_mouvements_generaux[%s]');
  	}
        
        public function doUpdateObject($values) {
            parent::doUpdateObject($values);
        }

}