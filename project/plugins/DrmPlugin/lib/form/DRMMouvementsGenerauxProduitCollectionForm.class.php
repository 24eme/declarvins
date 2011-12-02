<?php

class DRMMouvementsGenerauxProduitCollectionForm extends sfForm {

	public function configure()
	{
    	for ($i=0; $i<$this->getOption('nb_produit', 1); $i++) {
    		$this->embedForm ($i, new DRMMouvementsGenerauxProduitForm());
    	}
  	}

}