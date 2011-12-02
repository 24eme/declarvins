<?php

class drm_recapComponents extends sfComponents {

    public function executePopupAppellationAjout() {
        if (is_null($this->form)) {
            $this->form = new DRMAppellationAjoutForm($this->getUser()->getDrm()->declaration->labels->add($this->label->getKey())->appellations);
        }
    }
    
    public function executeItemForm() {
        if (is_null($this->form) || $this->form->getObject()->getCouleur()->getKey() != $this->detail->getCouleur()->getKey() || $this->form->getObject()->getKey() != $this->detail->getKey()) {
            $this->form = new DRMDetailForm($this->detail);
        }
    }

}
