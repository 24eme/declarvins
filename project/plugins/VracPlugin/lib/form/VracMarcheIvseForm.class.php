<?php
class VracMarcheIvseForm extends VracMarcheForm 
{
    public function configure() {
        parent::configure();

        unset($this['annexe']);
    }
}