<?php
class DRMMouvementsGenerauxForm extends acCouchdbObjectForm 
{
	protected $configuration;
	
	public function __construct($configuration, acCouchdbJson $object, $options = array(), $CSRFSecret = null)
	{
		$this->configuration = $configuration;
		parent::__construct($object, $options, $CSRFSecret);
	}
	public function configure() 
	{
		$this->setWidgets(array(
        	'pas_de_mouvement' => new sfWidgetFormInputCheckbox()
		));
		$this->widgetSchema->setLabels(array(
        	'pas_de_mouvement' => 'Pas de mouvement '
        ));
		$this->setValidators(array(
        	'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false))
        ));
        $certifications = $this->getObject()->declaration->certifications->toArray();
		foreach ($certifications as $certification => $value) {
				if ($this->getObject()->declaration->certifications->exist($certification)) {
	                $details = $this->getObject()->declaration->certifications->get($certification)->getProduits();
	                $this->embedForm($certification, new DRMMouvementsGenerauxCollectionProduitForm($details));
				}
		}
		$this->widgetSchema->setNameFormat('mouvements_generaux[%s]');
    }

    protected function updateDefaultsFromObject() 
    {
		parent::updateDefaultsFromObject();
        $this->setDefault('pas_de_mouvement', !$this->getObject()->declaration->hasMouvementCheck());
    }

    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $embedForm) {
        	$embedForm->doUpdateObject($values[$key]);
        }
    }
}