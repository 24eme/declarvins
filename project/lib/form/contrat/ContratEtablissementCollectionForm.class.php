<?php
class ContratEtablissementCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$contrat = $this->getOption('contrat'))
      throw new InvalidArgumentException('You must provide a contrat object.');
      
    for ($i=0; $i<$this->getOption('nbEtablissement', 1); $i++) {
    	$this->embedForm ($i, new ContratEtablissementNouveauForm($contrat->etablissements->add()));
    }
  }
}