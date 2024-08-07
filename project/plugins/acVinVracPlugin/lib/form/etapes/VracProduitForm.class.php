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
	        	'produit' => 'Appellation ou dénomination concernée*:',
	             'millesime' => 'Millesime:',
            	'labels_arr' => 'Certifications / Labels*:',
            	'mentions' => 'Mentions:',
        		'labels_libelle_autre' => 'Précisez le label autre:',
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
            $this->editablizeInputPluriannuel();
	        $this->setWidget('non_millesime', new sfWidgetFormInputCheckbox());
	        $this->widgetSchema->setLabel('non_millesime', '&nbsp;');
	        $this->setValidator('non_millesime', new ValidatorPass());



		    if ($this->getObject()->hasVersion() && $this->getObject()->volume_enleve > 0) {
		      	$this->setWidget('produit', new sfWidgetFormInputHidden());
		      }

             if ($this->configuration->isContratPluriannuelActif() && $this->getObject()->isPluriannuel()) {
                 unset($this['millesime']);
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
          $this->getObject()->interpro = $configurationProduit->getDocument()->interpro;
          $this->getObject()->contrat_pluriannuel = ($this->getObject()->isPluriannuel())? 1 : 0;
        	$cvo = $configurationProduit->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, null, true);
	        if ($cvo) {
	        	$this->getObject()->part_cvo = $cvo->taux;
	        }
        }
        $this->getObject()->update();
        $this->getObject()->initClauses();
        if (!isset($values['labels_arr'])) {
            $this->getObject()->labels_libelle_autre = null;
        } elseif (!in_array('autre', $values['labels_arr'])) {
            $this->getObject()->labels_libelle_autre = null;
        }
        if (!isset($values['mentions'])) {
            $this->getObject()->mentions_libelle_autre = null;
            $this->getObject()->mentions_libelle_chdo = null;
            $this->getObject()->mentions_libelle_marque = null;
        } elseif (!in_array('chdo', $values['mentions'])) {
            $this->getObject()->mentions_libelle_chdo = null;
        } elseif (!in_array('autre', $values['mentions'])) {
            $this->getObject()->mentions_libelle_autre = null;
        } elseif (!in_array('marque', $values['mentions'])) {
            $this->getObject()->mentions_libelle_marque = null;
        }
        $this->getObject()->labels_libelle = $this->getConfiguration()->formatLabelsLibelle($this->getObject()->getLibellesLabels());
        $this->getObject()->mentions_libelle = $this->getConfiguration()->formatMentionsLibelle($this->getObject()->getLibellesMentions());
    }

	protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($this->getObject()->produit) {
        	preg_match('/([0-9a-zA-Z\/]+)\/cepages\/[0-9a-zA-Z\/]+/', $this->getObject()->produit, $matches);
        	$this->setDefault('produit', '/'.str_replace('/declaration/', 'declaration/', $matches[1]));
        }
      	if (!$this->getObject()->millesime && $this->getObject()->volume_propose && !$this->getObject()->isAdossePluriannuel()) {
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
