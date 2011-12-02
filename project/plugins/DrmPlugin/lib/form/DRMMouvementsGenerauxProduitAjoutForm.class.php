<?php

class DRMMouvementsGenerauxProduitAjoutForm extends sfForm {
	
    const FORM_NAME = 'drm_mouvements_generaux[%s]';
    const EMBED_FORM_NAME = 'produit';

	public function configure()
	{
    	$formProduits = new DRMMouvementsGenerauxProduitCollectionForm(null, array(
	    	'nb_produit'    => $this->getOption('nb_produit', 1)
	  	));
  		$this->embedForm(self::EMBED_FORM_NAME, $formProduits);
        $this->widgetSchema->setNameFormat(self::FORM_NAME);
  	}
  	
    public static function getNewItem($numeroProduitSuivant) {
        $form_container = new BaseForm();
        $form_container->getWidgetSchema()->setNameFormat(self::FORM_NAME);
        $form = new BaseForm();
        $form->embedForm($numeroProduitSuivant, new DRMMouvementsGenerauxProduitForm());
        $form_container->embedForm(self::EMBED_FORM_NAME, $form);
        return $form_container;
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}