<?php
class VracSoussigneIrForm extends VracSoussigneForm
{
    public function configure() {
        parent::configure();
        $this->validatorSchema->setPostValidator(new VracSoussigneIrValidator($this->getObject()));
    }

    protected function doUpdateObject($values) {
		parent::doUpdateObject($values);
        if ($this->getObject()->tiersIsPacteCooperatif()) {
            $this->getObject()->setPacteCooperatif();
        }
	}
}
