<?php
class ContratEtablissementCollectionForm extends acCouchdbForm
{
  public function configure()
  {      
    for ($i=0; $i<$this->getOption('nbEtablissement', 1); $i++) {
    	$this->embedForm ($i, new ContratEtablissementNouveauForm($this->getDocument()->etablissements->add()));
    }
  }
}