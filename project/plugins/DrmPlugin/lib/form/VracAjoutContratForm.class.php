<?php

class VracAjoutContratForm extends acCouchdbFormDocumentJson 
{
	protected $_contrat_choices;
	
    public function configure() 
    {
        $this->setWidgets(array(
            'vrac' => new sfWidgetFormChoice(array('choices' => $this->getContratChoices())),
        ));
        $this->widgetSchema->setLabels(array(
        	'vrac' => 'Contrat*: ',
        ));
        $this->setValidators(array(
            'vrac' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContratChoices())), array('required' => 'Champ obligatoire')),
        ));
        $this->widgetSchema->setNameFormat('vrac_'.$this->getObject()->getIdentifiant().'[%s]');
    }

    public function doUpdateObject($values) {
    	$this->getObject()->vrac->add($values['vrac']);
    }
    
	public function getContratChoices() 
    {
        if (is_null($this->_contrat_choices)) {
        	$etablissement = $this->getObject()->getDocument()->identifiant;
            $this->_contrat_choices = array('' => '');
            foreach (VracClient::getInstance()->getAll() as $contrat) {
                if ($contrat->etablissement == $etablissement && (strpos($this->getObject()->getHash(), $contrat->produit) !== false) && !$this->getObject()->vrac->exist($contrat->numero)) {
                    $this->_contrat_choices[$contrat->numero] = $contrat->numero;
                }
            }
        }
        return $this->_contrat_choices;
    }

}