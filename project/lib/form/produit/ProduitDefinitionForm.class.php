<?php
class ProduitDefinitionForm extends acCouchdbFormDocumentJson {

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
    		$nbDepartements = (count($this->getNoeudDepartement()) > 0)? count($this->getNoeudDepartement()) : 1;
			$this->embedForm(
				'departements', 
				new ProduitDepartementCollectionForm(null, array('departements' => $this->getNoeudDepartement(), 'nbDepartement' => $this->getOption('nbDepartement', $nbDepartements)))
			);
		}
		if ($this->getObject()->hasDroits()) {
    		$nbDouane = (count($this->getNoeudDroit('douane')) > 0)? count($this->getNoeudDroit('douane')) : 1;
    		$nbCvo = (count($this->getNoeudDroit('cvo')) > 0)? count($this->getNoeudDroit('cvo')) : 1;
			$this->embedForm(
				'douane', 
				new ProduitDroitCollectionForm(null, array('droits' => $this->getNoeudDroit('douane'), 'nbDroits' => $this->getOption('nbDouane', $nbDouane)))
			);
			$this->embedForm(
				'cvo', 
				new ProduitDroitCollectionForm(null, array('droits' => $this->getNoeudDroit('cvo'), 'nbDroits' => $this->getOption('nbCvo', $nbCvo)))
			);
		}
		if ($this->getObject()->hasLabel()) {
    		$nbLabel = (count($this->getNoeudLabel()) > 0)? count($this->getNoeudLabel()) : 1;
			$this->embedForm(
				'labels', 
				new ProduitLabelCollectionForm(null, array('labels' => $this->getNoeudLabel(), 'nbLabel' => $this->getOption('nbLabel', $nbLabel)))
			);
		}
        $this->widgetSchema->setNameFormat('produit_definition[%s]');
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
}