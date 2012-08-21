<?php
class VracMarcheCivpForm extends VracMarcheForm 
{

    public function configure() {
        parent::configure();

        unset($this['has_transaction']);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $this->getObject()->has_transaction = 1;
    }
}