<?php
class LoginContratForm extends BaseForm 
{
	
    protected $_choices_comptes;
  
    public function configure() {
        $this->setWidgets(array(
                'contrat' => new sfWidgetFormChoice(array('choices' => $this->getComptes()))
        ));

        $this->widgetSchema->setLabels(array(
                'contrat' => 'Compte : '
        ));

        $this->setValidators(array(
                'contrat'  =>  new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getComptes())),array('required' => "Aucun compte n'a été saisi !"))
        ));
        
        $this->mergePostValidator(new ValidatorContrat());
        $this->widgetSchema->setNameFormat('login_contrat[%s]');
    }
    
    public function getComptes() {
        if (is_null($this->_choices_comptes)) {
        	
            $this->_choices_comptes = CompteDeclarantsView::getInstance()->formatComptes();
            $this->_choices_comptes[""] = "";
            ksort($this->_choices_comptes);
        }

        return $this->_choices_comptes;
    }
}