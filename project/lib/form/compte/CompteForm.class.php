<?php
abstract class CompteForm extends BaseForm {
    
    protected $_compte = null;
    
    /**
     *
     * @param Compte $compte
     * @param array $options
     * @param string $CSRFSecret 
     */
    public function __construct(_Compte $compte, $options = array(), $CSRFSecret = null) {
        $this->_compte = $compte;
        $this->checkCompte();
        parent::__construct(array('email' => $this->_compte->email), $options, $CSRFSecret);
    }
    
    /**
     * 
     */
    protected function checkCompte() {
        if (!$this->_compte) {
            throw new sfException("compte does exist");
        }
    }
    
    /**
     * 
     */
    public function configure() {
        $this->setWidgets(array(
                'email' => new sfWidgetFormInputText(),
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword()
        ));

        $this->widgetSchema->setLabels(array(
                'email' => 'Adresse e-mail: ',
                'mdp1'  => 'Mot de passe: ',
                'mdp2'  => 'Vérification du mot de passe: '
        ));

        $this->widgetSchema->setNameFormat('compte[%s]');

        $this->setValidators(array(
                'email' => new sfValidatorEmail(array('required' => true),array('required' => 'Champ obligatoire', 'invalid' => 'Adresse email invalide.')),
                'mdp1'  => new sfValidatorString(array('required' => true, "min_length" => "4"), array('required' => 'Champ obligatoire', "min_length" => "Votre mot de passe doit comporter au minimum 4 caractères.")),
                'mdp2'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
        ));
        //$this->validatorSchema->setPostValidator(new ValidatorCreateCompte());
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'The passwords must match')));
    }
    
    /**
     * 
     * @return _Compte
     */
    public function save() {
        throw new sfException("method not defined");
    }
}
