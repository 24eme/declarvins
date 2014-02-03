<?php

class vracExpirationTask extends sfBaseTask
{
	const NB_JOUR_EXPIRATION = 10;
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'vrac';
    $this->name             = 'expiration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importEtablissement|INFO] task does things.
Call it with:

  [php symfony vracExpiration|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    set_time_limit(600);
    
    $vracs = VracHistoryView::getInstance()->findLast();
    foreach ($vracs->rows as $vrac) {
    	$values = $vrac->value;
    	$this->sendExpiration($values);		
    }
  }
  
  protected function sendExpiration($values) {
  	$today = new DateTime();
	$datesaisie = new DateTime($values[VracHistoryView::VRAC_VIEW_DATESAISIE]);
	$interval = $today->diff($datesaisie);
	$ecart = $interval->format('%a');
  	if ($ecart >= self::NB_JOUR_EXPIRATION && $values[VracHistoryView::VRAC_VIEW_DATERELANCE]) {
  		$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  		
  		$acteurs = VracClient::getInstance()->getActeurs();
		foreach ($acteurs as $acteur) {
			if ($email = $vrac->get($acteur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
				if ($etablissement->compte) {
					if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
						if ($compte->statut == _Compte::STATUT_ARCHIVE) {
							if ($interpro->email_contrat_vrac) {
								Email::getInstance(sfContext::createInstance($this->configuration))->vracExpirationContrat($vrac, $etablissement, $interpro->email_contrat_vrac);
							}
						}
					}
				}
				Email::getInstance(sfContext::createInstance($this->configuration))->vracExpirationContrat($vrac, $etablissement, $email);
			}
		}
  		$this->logSection('vrac-expiration', 'Expiration du contrat '.$vrac->_id);
  		$vrac->delete();
  	}
  }
}
