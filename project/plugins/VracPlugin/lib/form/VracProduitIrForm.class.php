<?php
class VracProduitIrForm extends VracProduitForm 
{
    public function configure() {
        parent::configure();
        $this->getWidget('labels_libelle_autre')->setLabel('Précisez le label*:');
        $this->getWidget('mentions_libelle_autre')->setLabel('Précisez la mention autre*:');
        $this->getWidget('mentions_libelle_chdo')->setLabel('Précisez le terme règlementé*:');
        $this->getWidget('mentions_libelle_marque')->setLabel('Précisez la marque*:');
        $this->validatorSchema->setPostValidator(new VracProduitIrValidator());
    }
}