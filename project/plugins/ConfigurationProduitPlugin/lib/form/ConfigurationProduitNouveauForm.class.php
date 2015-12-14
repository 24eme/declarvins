<?php
class ConfigurationProduitNouveauForm extends sfForm 
{
	protected $configurationProduit;
	protected static $configurationNoeud = array('certifications' => 'certification', 'genres' => 'genre', 'appellations' => 'appellation', 'lieux' => 'lieu', 'couleurs' => 'couleur', 'cepages' => 'cepage'); 
	protected static $certifications = array('' => '', 'AOP' => 'AOP', 'IGP' => 'IGP', 'VINSSANSIG' => 'SANS IG', 'LIE' => 'LIE', 'MOUTS' => 'MOÛTS');
	protected static $couleurs = array('' => '', 'rouge' => 'Rouge', 'blanc' => 'Blanc', 'rose' => 'Rosé');
	protected static $genres = array('' => '', 'EFF' => 'Effervescent', 'TRANQ' => 'Tranquilles', 'VDN' => 'Vin doux naturel', 'VCI' => 'VCI');
	
	public function __construct($configuration, $defaults = array(), $options = array(), $CSRFSecret = null) 
	{
		$this->configurationProduit = $configuration;
		parent::__construct($defaults, $options, $CSRFSecret);
	}

    public function configure() 
    {
    	$certifications = self::$certifications;
    	$genres = self::$genres;
    	$appellations = array_merge(array(''=>''), $this->configurationProduit->getAppellations());
    	$lieux = array_merge(array(''=>''), $this->configurationProduit->getLieux());
    	$couleurs = self::$couleurs;
    	$cepages = array_merge(array(''=>''), $this->configurationProduit->getCepages());
    	
    	$this->setWidgets(array(
			'certifications' => new sfWidgetFormChoice(array('choices' => $certifications)),
    		'genres' => new sfWidgetFormChoice(array('choices' => $genres)),
			'appellations' => new sfWidgetFormChoice(array('choices' => $appellations)),  	
			'mentions' => new sfWidgetFormInputHidden(), 	
			'lieux' => new sfWidgetFormChoice(array('choices' => $lieux)), 	
			'couleurs' => new sfWidgetFormChoice(array('choices' => $couleurs)),
			'cepages' => new sfWidgetFormChoice(array('choices' => $cepages))
    	));
		$this->widgetSchema->setLabels(array(
			'certifications' => 'Catégorie: ',
			'genres' => 'Genre: ',
			'appellations' => 'Dénomination: ',  	
			'mentions' => 'Mention: ', 		
			'lieux' => 'Lieu: ', 	
			'couleurs' => 'Couleur: ', 
			'cepages' => 'Cépage: '
		));
		$this->setValidators(array(
			'certifications' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($certifications))),
			'genres' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($genres))),
			'appellations' => new sfValidatorString(array('required' => false)),
			'mentions' => new ValidatorPass(),
			'lieux' => new sfValidatorString(array('required' => false)),
			'couleurs' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($couleurs))),
			'cepages' => new sfValidatorString(array('required' => false))
		));
		
        $this->widgetSchema->setNameFormat('produit[%s]');
    }
    
    public function getProduit() 
    {
        $values = $this->getValues();
        $hash = 'declaration';
        $nodes = ConfigurationProduit::getArborescence();
        $noeud = null;
        $correspondanceNoeuds = ConfigurationProduit::getCorrespondanceNoeuds();
        foreach ($nodes as $node) {
	        if ($values[$node]) {
	        	if ($this->configurationProduit->exist($hash.'/'.$node.'/'.$values[$node])) {
	        		$hash = $hash.'/'.$node.'/'.$values[$node];
	        		unset($values[$node]);
	        	} else {
	        		if (!$noeud) {
	        			$noeud = $correspondanceNoeuds[$node];
	        		}
	        		$hash = $hash.'/'.$node.'/'.ConfigurationProduit::DEFAULT_KEY;
	        	}
	        } else {
	        	$hash = $hash.'/'.$node.'/'.ConfigurationProduit::DEFAULT_KEY;
	        	unset($values[$node]);
	        }
        }
        return array('hash' => $hash, 'noeud' => $noeud, 'values' => $values);
    }
    
}