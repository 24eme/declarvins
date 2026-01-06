<?php
class VracSoussigneIrForm extends VracSoussigneForm
{
    public function configure() {
        parent::configure();
        $this->validatorSchema->setPostValidator(new VracSoussigneIrValidator($this->getObject()));
    }
}
