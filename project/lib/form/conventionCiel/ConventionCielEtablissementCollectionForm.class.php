<?php
class ConventionCielEtablissementCollectionForm extends acCouchdbObjectForm
{
  public function configure()
  {      
    foreach ($this->getObject()->etablissements as $id => $etablissement) {
    	$this->embedForm ($id, new ConventionCielEtablissementForm($etablissement));
    }
  }
}