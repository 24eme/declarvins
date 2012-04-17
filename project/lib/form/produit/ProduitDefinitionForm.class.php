<?php
class ProduitDefinitionForm extends acCouchdbFormDocumentJson {
	
	public $hash = null;

    public function configure() {
    	$this->setWidgets(array(
			'libelle' => new sfWidgetFormInputText(),
			'code' => new sfWidgetFormInputText()  		
    	));
		$this->widgetSchema->setLabels(array(
			'libelle' => 'Libellé*: ',
			'code' => 'Code*: '
		));
		$this->setValidators(array(
			'libelle' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
			'code' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
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
        $this->widgetSchema->setNameFormat('produit_definition[%s]');
        $this->mergePostValidator(new ProduitDefinitionValidatorSchema());
    }
    
    public function getHash() {
    	return $this->hash;
    }
    
    public function setHash($hash) {
    	$this->hash = $hash;
    }
    
    private function getNoeudInterpro()
    {
    	return $this->getObject()->interpro->getOrAdd(sfContext::getInstance()->getUser()->getInterpro()->_id);
    }
    
    private function getNoeudDepartement()
    {
    	return $this->getObject()->getOrAdd('departements');
    }
    
    private function getNoeudDroit($type)
    {
    	return $this->getNoeudInterpro()->getOrAdd('droits')->getOrAdd($type);
    }
    
    private function getNoeudLabel()
    {
    	return $this->getNoeudInterpro()->getOrAdd('labels');
    }
    
    private function setLabel($code, $libelle) {
    	$labels = $this->getObject()->getDocument()->labels;
    	if ($labels->exist($code)) {
    		$labels->remove($code);
    	}
    	$label = $labels->add($code, $libelle);
    }
    
    private function setDroit($droit, $date, $taux, $code) {
    	$droit->date = $date;
    	$droit->taux = $taux;
    	$droit->code = $code;
    }
    
    public function save($con = null) {
    	parent::save($con);
    	$values = $this->getValues();
    	if ($this->getObject()->hasDepartements()) {
    		$this->getObject()->remove('departements');
    		$departements = $this->getNoeudDepartement();
    		foreach ($values['secteurs'] as $value) {
    			$departements->add(null, $value['departement']);
    		}
    	}
    	if ($this->getObject()->hasDroits()) {
    		$this->getNoeudInterpro()->droits->remove('douane');
    		foreach ($values['droit_douane'] as $value) {
    			$this->setDroit($this->getNoeudDroit('douane')->add(), $value['date'], $value['taux'], $value['code']);
    		}
    		$this->getNoeudInterpro()->droits->remove('cvo');
    		foreach ($values['droit_cvo'] as $value) {
    			$this->setDroit($this->getNoeudDroit('cvo')->add(), $value['date'], $value['taux'], $value['code']);
    		}
    	}
    	if ($this->getObject()->hasLabels()) {
    		$this->getNoeudInterpro()->remove('labels');
    		$labels = $this->getNoeudLabel();
    		foreach ($values['labels'] as $value) {
    			$this->setLabel($value['code'], $value['label']);
    			$labels->add(null, $value['code']);
    		}
    	}
    	$this->getObject()->getDocument()->save();
    	
    }
}