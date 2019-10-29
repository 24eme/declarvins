<?php
class VracProduitForm extends VracForm 
{
   	public function configure()
    {
    		$produits = $this->getProduits();
	    	$this->setWidgets(array(
	        	'produit' => new sfWidgetFormChoice(array('choices' => $produits), array('class' => 'autocomplete')),
	    	    'millesime' => new sfWidgetFormInputText(),
            	'labels_arr' => new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true, 'choices' => $this->getLabels())),
            	'mentions' => new sfWidgetFormChoice(array('expanded' => true, 'choices' => $this->getMentions(), 'multiple' => true)),
        		'labels_libelle_autre' => new sfWidgetFormInputText(),
        		'mentions_libelle_autre' => new sfWidgetFormInputText(),
        		'mentions_libelle_chdo' => new sfWidgetFormInputText(),
        		'mentions_libelle_marque' => new sfWidgetFormInputText(),
	    	));
	        $this->widgetSchema->setLabels(array(
	        	'produit' => 'Produit*:',
	             'millesime' => 'Millesime:',
            	'labels_arr' => 'Certifications / Labels*:',
            	'mentions' => 'Mentions:',
        		'labels_libelle_autre' => 'Précisez le label:',
                'mentions_libelle_autre' => 'Précisez la mention autre:',
        		'mentions_libelle_chdo' => 'Précisez le terme règlementé:',
        		'mentions_libelle_marque' => 'Précisez la marque:',
	        ));
	        $this->setValidators(array(
	        	'produit' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($produits))),
	            'millesime' => new sfValidatorRegex(array('required' => false, 'pattern' => '/^(20)[0-9]{2}$/')),
            	'labels_arr' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getLabels()), 'multiple' => true)),
            	'mentions' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getMentions()), 'multiple' => true)),
        		'labels_libelle_autre' => new sfValidatorString(array('required' => false)),
        		'mentions_libelle_autre' => new sfValidatorString(array('required' => false)),
        		'mentions_libelle_chdo' => new sfValidatorString(array('required' => false)),
        		'mentions_libelle_marque' => new sfValidatorString(array('required' => false)),
	        ));
	        
	        $this->setWidget('non_millesime', new sfWidgetFormInputCheckbox());
	        $this->widgetSchema->setLabel('non_millesime', '&nbsp;');
	        $this->setValidator('non_millesime', new ValidatorPass());
    		
    
	        
		    if ($this->getObject()->hasVersion() && $this->getObject()->volume_enleve > 0) {
		      	$this->setWidget('produit', new sfWidgetFormInputHidden());
		      	$this->setWidget('millesime', new sfWidgetFormInputHidden());
            	unset($this['non_millesime']);
		      }
    		
    		
  		    $this->validatorSchema->setPostValidator(new VracProduitValidator());
    		$this->widgetSchema->setNameFormat('vrac_produit[%s]');
    }
    protected function doUpdateObject($values) {
    	$persit = null;
    	if (preg_match('/'.str_replace('/', '\/', $values['produit']).'/', $this->getObject()->produit)) {
    		$persit = $this->getObject()->produit;
    	}
        parent::doUpdateObject($values);
        $this->getObject()->produit = ($persit)? $persit : $values['produit'].'/cepages/'.ConfigurationProduit::DEFAULT_KEY;
        $configuration = ConfigurationClient::getCurrent();
        $configurationProduit = $configuration->getConfigurationProduit($this->getObject()->produit);
        if ($configurationProduit) {
        	$this->getObject()->setDetailProduit($configurationProduit);
        	$this->getObject()->produit_libelle = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles());
        	$cvo = $configurationProduit->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, null, true);
	        if ($cvo) {
	        	$this->getObject()->part_cvo = $cvo->taux;
	        }
        }
        $this->getObject()->update();
        $interpro = $this->getObject()->getProduitInterpro();
        if ($interpro && $interpro->identifiant == 'CIVP' && !sfContext::getInstance()->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $this->getObject()->has_transaction = 1;
        } else {
            $this->getObject()->has_transaction = 0;
        }
        $this->getObject()->labels_libelle = $this->getConfiguration()->formatLabelsLibelle(array($this->getObject()->labels));
        $this->getObject()->mentions_libelle = $this->getConfiguration()->formatMentionsLibelle($this->getObject()->mentions);
    }
    
	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->getObject()->produit) {
        	preg_match('/([0-9a-zA-Z\/]+)\/cepages\/[0-9a-zA-Z\/]+/', $this->getObject()->produit, $matches);
        	$this->setDefault('produit', '/'.str_replace('/declaration/', 'declaration/', $matches[1]));
        }  
      	if (!$this->getObject()->millesime && $this->getObject()->volume_propose) {
        		$this->setDefault('non_millesime', true);
        } 
        if (!(count($this->getObject()->labels_arr->toArray()) > 0)) {
            $this->setDefault('labels_arr', '');
        }  
        if (!(count($this->getObject()->mentions->toArray()) > 0)) {
           $this->setDefault('mentions', '');
        }  
    }
}