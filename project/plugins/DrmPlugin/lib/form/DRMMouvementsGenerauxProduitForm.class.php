<?php

class DRMMouvementsGenerauxProduitForm extends sfForm { //acCouchdbFormDocumentJson {
	
    const FORM_NAME = 'drm_mouvements_generaux[%s]';
    const EMBED_FORM_NAME = 'produit';
    
    protected $_produit;

    public function __construct(DRM $drm) {
    	if (!$drm) {
    		throw new sfException('DRM Object needed');
    	}
    	$this->_drm = $drm;
    	parent::__construct();
    }
    
	public function configure()
	{
    	$formProduits = new DRMMouvementsGenerauxProduitCollectionForm($this->_drm);
  		$this->embedForm(self::EMBED_FORM_NAME, $formProduits);
        $this->widgetSchema->setNameFormat(self::FORM_NAME);
  	}
  	
    public static function getNewItem($numeroProduitSuivant, $produit) {
        $form_container = new BaseForm();
        $form_container->getWidgetSchema()->setNameFormat(self::FORM_NAME);
        $form = new BaseForm();
        $form->embedForm($numeroProduitSuivant, new DRMMouvementsGenerauxProduitForm($produit));
        $form_container->embedForm(self::EMBED_FORM_NAME, $form);
        return $form_container;
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}