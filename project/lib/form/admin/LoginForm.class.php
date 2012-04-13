<?php
class LoginForm extends BaseForm {
    
    protected $_interproCollection = null;
    /**
     * 
     */
    public function configure() {
        $choices = $this->getInterprosChoices();
        $this->setWidgets(array(
                'interpro' => new sfWidgetFormChoice(array('choices' => $choices))
        ));

        $this->widgetSchema->setLabels(array(
				'interpro' => 'Se connecter en tant que :'
        ));

        $this->setValidators(array(
                'interpro' => new sfValidatorChoice(array('choices' => array_keys($choices)))
        ));
        
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
            return $this->_interproCollection = acCouchdbManager::getClient('Interpro')->getAll();
        }
        else {
            return $this->_interproCollection;
        }
    }
}

