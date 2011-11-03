<?php
class LoginForm extends BaseForm {
    
    protected $_interproCollection = null;
    /**
     * 
     */
    public function configure() {
        $choices = $this->getInterprosChoices();
        $this->setWidgets(array(
                'interpro' => new sfWidgetFormChoice(array('choices' => $choices)),
                'contrat' => new sfWidgetFormInputText(),
        ));

        $this->widgetSchema->setLabels(array(
					     'interpro' => 'Se connecter en tant que :',
                'contrat' => 'Contrat numéro : '
        ));

        $this->setValidators(array(
                'interpro' => new sfValidatorChoice(array('choices' => array_keys($choices))),
                'contrat'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
        ));
        
        $this->mergePostValidator(new ValidatorContrat());
        $this->widgetSchema->setNameFormat('login[%s]');
    }
    
    /**
     * 
     */
    protected function getInterprosChoices() {
        $interpros = $this->getInterpros();
        $choices = array();
        foreach ($interpros as $interpro) {
            $choices[$interpro->get('_id')] = $interpro->getNom();
        }
        return $choices;
    }
    
    /**
     * 
     */
    protected function getInterpros() {
        if (!$this->_interproCollection) {
            return $this->_interproCollection = sfCouchdbManager::getClient('Interpro')->getAll();
        }
        else {
            return $this->_interproCollection;
        }
    }
}

