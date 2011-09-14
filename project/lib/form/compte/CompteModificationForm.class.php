<?php
class CompteModificationForm extends CompteForm {
    
    /**
     * 
     */
    public function configure() {
        parent::configure();
        $this->getValidator('mdp1')->setOption('required', false);
        $this->getValidator('mdp2')->setOption('required', false);
    }
    
    /**
     *
     * @return _Compte 
     */
    public function save() {
        if ($this->isValid()) {
            if ($this->getValue('mdp1')) {
                $this->_compte->setPasswordSSHA($this->getValue('mdp1'));
            }
            $this->_compte->email = $this->getValue('email');
            $this->_compte->save();
        } else {
            throw new sfException("form must be valid");
        }
        
        return $this->_compte;
    }
    
}
