<?php
class VracMarcheIrForm extends VracMarcheForm 
{
    public function configure() {
        parent::configure();

        unset($this['annexe']);
    }
}