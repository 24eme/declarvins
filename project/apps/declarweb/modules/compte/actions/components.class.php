<?php

class compteComponents extends sfComponents {
  public function executeValidation()
  {
    if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
        $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
        if ($interpro->getStatut() == _Compte::STATUT_VALIDATION_ATTENTE) {
            $this->message = 'Vous n\'avez pas valider le compte';
            $this->valide = false;
        }
        else {
            $this->message = 'Vous avez valider le compte';
            $this->valide = true;
        }
       
    }
    else {
        $this->message = 'Vous n\'avez pas valider le compte';
        $this->valide = false;
    }
  }
}

