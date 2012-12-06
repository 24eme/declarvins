<?php
class ProduitDefinitionForm extends acCouchdbObjectForm {
	
	public $hash = null;

    public function configure() {
    	$this->setWidgets(array(
			'libelle' => new sfWidgetFormInputText(),
			'code' => new sfWidgetFormInputText()  		
    	));
		$this->widgetSchema->setLabels(array(
			'libelle' => 'Libellé: ',
			'code' => 'Code: '
		));
		$this->setValidators(array(
			'libelle' => new sfValidatorString(array('required' => false), array('required' => 'Champ obligatoire')),
			'code' => new sfValidatorString(array('required' => false), array('required' => 'Champ obligatoire'))
		));
		if ($this->getObject()->hasDepartements()) {
			$this->embedForm(
				'secteurs', 
				new ProduitDepartementCollectionForm(null, array('departements' => $this->getNoeudDepartement(), 'nb' => $this->getOption('nbDepartement', null)))
			);
		}
		if ($this->getObject()->hasDroits()) {
    		$nbDouane = (count($this->getNoeudDroit('douane')) > 0)? count($this->getNoeudDroit('douane')) : 1;
    		$nbCvo = (count($this->getNoeudDroit('cvo')) > 0)? count($this->getNoeudDroit('cvo')) : 1;
			$this->embedForm(
				'droit_douane', 
				new ProduitDroitCollectionForm(null, array('droits' => $this->getNoeudDroit('douane'), 'nb' => $this->getOption('nbDouane', null)))
			);
			$this->embedForm(
				'droit_cvo', 
				new ProduitDroitCollectionForm(null, array('droits' => $this->getNoeudDroit('cvo'), 'nb' => $this->getOption('nbCvo', null)))
			);
		}
		if ($this->getObject()->hasLabels()) {
			$this->embedForm(
				'labels', 
				new ProduitLabelCollectionForm(null, array('labels' => $this->getNoeudLabel(), 'nb' => $this->getOption('nbLabel', null)))
			);
		}
		if ($this->getObject()->hasDetails()) {
			$this->embedForm(
				'detail', 
				new ProduitDetailsForm($this->getObject()->getOrAdd('detail'))
			);
		}
        $this->widgetSchema->setNameFormat('produit_definition[%s]');
        $this->mergePostValidator(new ProduitDefinitionValidatorSchema());
    }
    
    public function getHash() {
    	return $this->hash;
    }
    
    public function setHash($hash) {
    	$this->hash = $hash;
    }
    
    private function getNoeudInterpro($object = null)
    {
    	if (!$object) {
    		$object = $this->getObject();
    	}
    	return $object->interpro->getOrAdd($this->getUser()->getCompte()->getGerantInterpro()->_id);
    }
    
    private function getNoeudDepartement($object = null)
    {
    	if (!$object) {
    		$object = $this->getObject();
    	}
    	return $object->getOrAdd('departements');
    }
    
    private function getNoeudDroit($type, $object = null)
    {
    	if (!$object) {
    		$object = $this->getObject();
    	}
    	return $this->getNoeudInterpro($object)->getOrAdd('droits')->getOrAdd($type);
    }
    
    private function getNoeudLabel($object = null)
    {
    	if (!$object) {
    		$object = $this->getObject();
    	}
    	return $this->getNoeudInterpro($object)->getOrAdd('labels');
    }
    
    private function setLabel($code, $libelle) {
    	$labels = $this->getObject()->getDocument()->labels;
    	if (!$labels->exist($code)) {
    		$label = $labels->add($code, $libelle);
    	}
    }
    
    private function setDroit($code, $libelle) {
    	$droits = $this->getObject()->getDocument()->droits;
    	if (!$droits->exist($code)) {
    		$droit = $droits->add($code, $libelle);
    		if (preg_match('/^([^_]+)_/', $code, $m)) {
				if (!$droits->exist($m[1])) {
					$droit = $droits->add($m[1], null);
				}
			}
    	}
    }
    
    private function setNodeDroit($droit, $date, $taux, $code, $libelle) {
    	$droit->date = $date;
    	$droit->taux = $taux;
    	$droit->code = $code;
    	$droit->libelle = $libelle;
    }
    
    private function replaceKey($hash, $key) {
    	$hash = explode('/', $hash);
    	$hash[count($hash) - 1] = $key;
    	return implode('/', $hash);
    }
    
    private function normalizeKey($key, $uppercase = true) {
    	$key = sfInflector::underscore($key);
    	if ($uppercase) {
    		$key = strtoupper($key);
    	}
    	return $key;
    }
    
    public function save($con = null) {
    	$object = parent::save($con);
    	$values = $this->getValues();
    	if (!empty($values['code']) && $object->getKey() != $values['code']) {
    		$object = $object->getDocument()->moveAndClean($object->getHash(), $this->replaceKey($object->getHash(), $this->normalizeKey($values['code'], (($object->getTypeNoeud() == ConfigurationCouleur::TYPE_NOEUD)? false : true))));
    	}
    	if ($object->hasDepartements()) {
    		$object->remove('departements');
    		$departements = $this->getNoeudDepartement($object);
    		foreach ($values['secteurs'] as $value) {
    			$departements->add(null, $value['departement']);
    		}
    	}
    	if ($object->hasDroits()) {
    		$this->getNoeudInterpro($object)->droits->remove('douane');
    		foreach ($values['droit_douane'] as $value) {
    			if ($value['taux'] > 0) {
    				$this->setDroit($value['code'], $value['libelle']);
    				$date = $value['date'];
    				if ($date) {
    					$date = explode('/', $date);
    					$date = new DateTime($date[2].'-'.$date[1].'-'.$date[0]);
    				}
    				$this->setNodeDroit($this->getNoeudDroit('douane', $object)->add(), $date->format('c'), $value['taux'], $value['code'], $value['libelle']);
    			}
    		}
    		$this->getNoeudInterpro()->droits->remove('cvo');
    		foreach ($values['droit_cvo'] as $value) {
    			if ($value['taux'] > 0) {
    				$this->setDroit($value['code'], $value['libelle']);
    				$date = $value['date'];
    				if ($date) {
    					$date = explode('/', $date);
    					$date = new DateTime($date[2].'-'.$date[1].'-'.$date[0]);
    				}
    				$this->setNodeDroit($this->getNoeudDroit('cvo', $object)->add(), $date->format('c'), $value['taux'], $value['code'], $value['libelle']);
    			}
    		}
    	}
    	if ($object->hasLabels()) {
    		$this->getNoeudInterpro($object)->remove('labels');
    		$labels = $this->getNoeudLabel($object);
    		foreach ($values['labels'] as $value) {
    			$this->setLabel($value['code'], $value['label']);
    			$labels->add(null, $value['code']);
    		}
    	}
    	$object->getDocument()->save();
    	return $object;    	
    }

    protected function getUser() {

        return sfContext::getInstance()->getUser();
    }
}