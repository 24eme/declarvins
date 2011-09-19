<?php

class compteComponents extends sfComponents {
  public function executeValidation()
  {
    $this->valide_interpro = false;
    if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
        $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
        if ($interpro->getStatut() != _Compte::STATUT_VALIDATION_ATTENTE) {
            $this->valide_interpro = true;
        }
    }
    $this->compte_active = ($this->compte->getStatut() == _Compte::STATUT_ACTIVE);
  }
}

