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
        	'pas_de_mouvement' => new sfWidgetFormInputCheckbox(),
        	'droits_acquittes' => new sfWidgetFormInputCheckbox()
		));
		$this->widgetSchema->setLabels(array(
        	'pas_de_mouvement' => 'Pas de mouvement ',
        	'droits_acquittes' => 'Droits acquittés '
        ));
		$this->setValidators(array(
        	'pas_de_mouvement' => new sfValidatorBoolean(array('required' => false)),
        	'droits_acquittes' => new sfValidatorBoolean(array('required' => false))
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
        if (!$this->getObject()->hasDroitsAcquittes()) {
        	$this->setDefault('droits_acquittes', null);
        }
    }

    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $embedForm) {
        	$embedForm->doUpdateObject($values[$key]);
        }
        if (isset($values['droits_acquittes']) && $values['droits_acquittes']) {
        	$this->getObject()->setHasDroitsAcquittes(1);
        } else {
        	$this->getObject()->setHasDroitsAcquittes(0);
        }
    }
}