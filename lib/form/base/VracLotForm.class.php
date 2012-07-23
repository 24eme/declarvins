<?php
class VracLotForm extends acCouchdbObjectForm
{
	protected $configuration;
    
	public function __construct(ConfigurationVrac $configuration, acCouchdbJson $object, $options = array(), $CSRFSecret = null) 
	{
        $this->setConfiguration($configuration);
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    public function getConfiguration()
    {
    	return $this->configuration;
    }
    
    public function setConfiguration($configuration)
    {
    	$this->configuration = $configuration;
    }
    
	public function configure()
	{
		$this->setWidgets(array(
	       'numero' => new sfWidgetFormInputText(),
	       'contenance_cuve' => new sfWidgetFormInputFloat(),
	       'millesime' => new sfWidgetFormInputText(),
		   'pourcentage_annee' => new sfWidgetFormInputFloat(),
	       'degre' => new sfWidgetFormInputFloat(),
	       'presence_allergenes' => new sfWidgetFormInputCheckbox(),
	       'raisin_quantite' => new sfWidgetFormInputFloat(),
		   'jus_quantite' => new sfWidgetFormInputFloat(),
	       'bouteilles_quantite' => new sfWidgetFormInputFloat(),
	       'bouteilles_contenance_volume' => new sfWidgetFormInputText(),
	       'bouteilles_contenance_libelle' => new sfWidgetFormInputText(),
		   'volume' => new sfWidgetFormInputFloat(),
	       'date_retiraison' => new sfWidgetFormInputText(),
	       'commentaires' => new sfWidgetFormChoice(array('choices' => $this->getCommentaires()))
		));
		$this->widgetSchema->setLabels(array(
	       'numero' => 'Numéro du lot:',
	       'contenance_cuve' => 'Contenance de la cuve:',
	       'millesime' => 'Millésime:',
		   'pourcentage_annee' => 'Pourcentage du millésime:',
	       'degre' => 'Degré:',
	       'presence_allergenes' => 'Présence d\'allergènes?',
	       'raisin_quantite' => 'Quantité de raisin:',
		   'jus_quantite' => 'Quantité de jus:',
	       'bouteilles_quantite' => 'Quantité de bouteille:',
	       'bouteilles_contenance_volume' => 'Contenance de bouteille:',
	       'bouteilles_contenance_libelle' => 'Libellé de bouteille:',
		   'volume' => 'Volume:',
	       'date_retiraison' => 'date de retiraison:',
	       'commentaires' => 'Commentaires:'
		));
		$this->setValidators(array(
	       'numero' => new sfValidatorString(array('required' => false)),
	       'contenance_cuve' => new sfValidatorNumber(array('required' => false)),
	       'millesime' => new sfValidatorString(array('required' => false)),
		   'pourcentage_annee' => new sfValidatorNumber(array('required' => false)),
	       'degre' => new sfValidatorNumber(array('required' => false)),
	       'presence_allergenes' => new sfValidatorPass(),
	       'raisin_quantite' => new sfValidatorNumber(array('required' => false)),
		   'jus_quantite' => new sfValidatorNumber(array('required' => false)),
	       'bouteilles_quantite' => new sfValidatorNumber(array('required' => false)),
	       'bouteilles_contenance_volume' => new sfValidatorString(array('required' => false)),
	       'bouteilles_contenance_libelle' => new sfValidatorString(array('required' => false)),
		   'volume' => new sfValidatorNumber(array('required' => false)),
	       'date_retiraison' => new sfValidatorString(array('required' => false)),
	       'commentaires' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCommentaires()))),
		));
		$this->widgetSchema->setNameFormat('lot[%s]');
	}
    
    public function getCommentaires()
    {
    	return $this->getConfiguration()->getCommentairesLot()->toArray();
    }
}