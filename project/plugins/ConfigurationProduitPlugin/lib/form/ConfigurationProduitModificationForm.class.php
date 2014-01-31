<?php
class ConfigurationProduitModificationForm extends acCouchdbObjectForm 
{
	
	protected $hash;

    public function configure() 
    {
    	$this->setWidgets(array(
			'libelle' => new sfWidgetFormInputText(),
			'code' => new sfWidgetFormInputText()
    	));
		$this->widgetSchema->setLabels(array(
			'libelle' => 'Libellé: ',
			'code' => 'Code: '
		));
		$this->setValidators(array(
			'libelle' => new sfValidatorString(array('required' => false)),
			'code' => new sfValidatorString(array('required' => false))
		));
		
		if ($this->getObject()->hasDepartements()) {
			$this->embedForm(
				'noeud_departements', 
				new ConfigurationProduitDepartementCollectionForm(null, array('departements' => $this->getObject()->getOrAdd('departements'), 'nb' => $this->getOption('nbDepartement', null)))
			);
		}
		if ($this->getObject()->hasCvo()) {
			$this->embedForm(
				'noeud_droits_cvo', 
				new ConfigurationProduitDroitCollectionForm(null, array('droits' => $this->getObject()->getOrAdd('droits')->getOrAdd('cvo'), 'nb' => $this->getOption('nbCvo', null)))
			);
		}
		if ($this->getObject()->hasDouane()) {
			$this->embedForm(
				'noeud_droits_douane', 
				new ConfigurationProduitDroitCollectionForm(null, array('droits' => $this->getObject()->getOrAdd('droits')->getOrAdd('douane'), 'nb' => $this->getOption('nbDouane', null)))
			);
		}
		if ($this->getObject()->hasLabels()) {
			$this->embedForm(
				'noeud_labels', 
				new ConfigurationProduitLabelCollectionForm(null, array('labels' => $this->getObject()->getOrAdd('labels'), 'nb' => $this->getOption('nbLabel', null)))
			);
		}
		if ($this->getObject()->hasOIOC()) {
			$this->embedForm(
				'noeud_organismes', 
				new ConfigurationProduitOrganismeCollectionForm(null, array('organismes' => $this->getObject()->getOrAdd('organismes'), 'nb' => $this->getOption('nbOrganisme', null)))
			);
		}
		if ($this->getObject()->hasDefinitionDrm()) {
			$this->embedForm(
				'noeud_definition_drm', 
				new ConfigurationProduitDefinitionDrmForm(null, array('definition_drm' => $this->getObject()->getOrAdd('definition_drm')))
			);
		}
		if ($this->getObject()->hasDrmVrac()) {
         	$this->setWidget('drm_vrac',  new WidgetFormInputCheckbox());
        	$this->setValidator('drm_vrac', new ValidatorBoolean(array('required' => false)));
		}
        $this->widgetSchema->setNameFormat('produit_definition[%s]');
        $this->mergePostValidator(new ProduitDefinitionValidatorSchema($this->getObject()));
    }
    
    public function getHash() 
    {
    	return $this->hash;
    }
    
    public function setHash($hash) 
    {
    	$this->hash = $hash;
    }
    
    public function save($con = null) {
    	$object = parent::save($con);
    	$values = $this->getValues();
    	$isNew = $this->getOption('isNew', false);
    	if ($isNew && !empty($values['code']) && $object->getKey() != $values['code']) {
    		if ($object->getTypeNoeud() == ConfigurationProduitCouleur::TYPE_NOEUD) {
    			$values['code'] = $this->couleurKeyToCode($values['code']);
    		}
    		$object = $object->getDocument()->moveAndClean($object->getHash(), $this->replaceKey($object->getHash(), $this->normalizeKey($values['code'], (($object->getTypeNoeud() == ConfigurationProduitCouleur::TYPE_NOEUD)? false : true))));
    	}
    	
    	if ($object->hasDepartements()) {
    		$object->remove('departements');
    		$departements = $object->add('departements');
    		foreach ($values['noeud_departements'] as $value) {
    			$departements->add(null, $value['departement']);
    		}
    	}
    	if ($object->hasLabels()) {
    		$object->remove('labels');
    		$labels = $object->add('labels');
    		foreach ($values['noeud_labels'] as $value) {
    			$labels->add($value['code'], $value['label']);
    		}
    	}
    	if ($this->getObject()->hasCvo()) {
    		$object->droits->remove('cvo');
    		$droits = $object->droits->add('cvo');
    		foreach ($values['noeud_droits_cvo'] as $value) {
    			$date = $value['date'];
    			$date = explode('/', $date);
    			$date = $date[2].'-'.$date[1].'-'.$date[0];
    			$droit = $droits->add($date);
    			$droit->date = $date;
    			$droit->taux = $value['taux'];
    			$droit->code = $value['code'];
    			$droit->libelle = $value['libelle'];
    		}
		}
		if ($this->getObject()->hasDouane()) {
    		$object->droits->remove('douane');
    		$droits = $object->droits->add('douane');
    		foreach ($values['noeud_droits_douane'] as $value) {
    			$date = $value['date'];
    			$date = explode('/', $date);
    			$date = $date[2].'-'.$date[1].'-'.$date[0];
    			$droit = $droits->add($date);
    			$droit->date = $date;
    			$droit->taux = $value['taux'];
    			$droit->code = $value['code'];
    			$droit->libelle = $value['libelle'];
    		}
		}
		if ($this->getObject()->hasDefinitionDrm()) {
			$object->remove('definition_drm');
			$definitionDrm = $object->add('definition_drm');
			$definitionDrm->entree->repli = ($values['noeud_definition_drm']['entree_repli'])? 1 : 0;
			$definitionDrm->entree->declassement = ($values['noeud_definition_drm']['entree_declassement'])? 1 : 0;
			$definitionDrm->sortie->repli = ($values['noeud_definition_drm']['sortie_repli'])? 1 : 0;
			$definitionDrm->sortie->declassement = ($values['noeud_definition_drm']['sortie_declassement'])? 1 : 0;
		}
		if ($this->getObject()->hasOIOC()) {
    		$object->remove('organismes');
    		$organismes = $object->add('organismes');
    		foreach ($values['noeud_organismes'] as $value) {
    			$date = $value['date'];
    			$date = explode('/', $date);
    			$date = $date[2].'-'.$date[1].'-'.$date[0];
    			$organisme = $organismes->add($date);
    			$organisme->date = $date;
    			$organisme->oioc = $value['oioc'];
    		}
		}
    	$object->getDocument()->save();
    	return $object;
    }
    
    private function replaceKey($hash, $key) 
    {
    	$hash = explode('/', $hash);
    	$hash[count($hash) - 1] = $key;
    	return implode('/', $hash);
    }
    
    private function normalizeKey($key, $uppercase = true) 
    {
    	$key = sfInflector::underscore($key);
    	if ($uppercase) {
    		$key = strtoupper($key);
    	}
    	return $key;
    }
  
    private function couleurKeyToCode($key) 
    {
    	$correspondances = array(1 => "rouge",
                             2 => "rose",
                             3 => "blanc");

    	return $correspondances[$key];
    }
}