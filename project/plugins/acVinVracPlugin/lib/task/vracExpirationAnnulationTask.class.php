<?php

class vracExpirationAnnulationTask extends sfBaseTask
{
	const NB_JOUR_EXPIRATION = 3;
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'vrac';
    $this->name             = 'expiration-annulation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importEtablissement|INFO] task does things.
Call it with:

  [php symfony vracExpirationAnnulation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    set_time_limit(0);
    
    $vracs = VracAllView::getInstance()->findByStatut(VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION);
    $client = VracClient::getInstance();
    foreach ($vracs->rows as $values) {
    	$vrac = $client->find($values->id);
    	$dateAnnulation = null;
    	$dates = array();
    	if ($vrac->annulation->date_annulation_vendeur) {
    		$dates[] = $vrac->annulation->date_annulation_vendeur;
    	}
    	if ($vrac->annulation->date_annulation_acheteur) {
    		$dates[] = $vrac->annulation->date_annulation_acheteur;
    	}
    	if ($vrac->annulation->date_annulation_mandataire) {
    		$dates[] = $vrac->annulation->date_annulation_mandataire;
    	}
    	foreach ($dates as $date) {
    		if (!$dateAnnulation || $dateAnnulation > $date) {
    			$dateAnnulation = $date;
    		}
    	}
    	$today = new DateTime();
    	$annulation = new DateTime($dateAnnulation);
    	$interval = $today->diff($annulation);
    	$ecart = $interval->format('%a');
    	if ($ecart >= self::NB_JOUR_EXPIRATION) {
    		$this->sendExpiration($vrac);
    		$vrac->remove('annulation');
    		$vrac->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
    		$vrac->save();
    		$this->logSection('vrac-expiration-annulation', 'Expiration de l\'annulation du contrat '.$vrac->_id);
    	}		
    }
  }
  
  protected function sendExpiration($vrac) {
  	$routing = clone ProjectConfiguration::getAppRouting();
	$contextInstance = sfContext::createInstance($this->configuration);
    $contextInstance->set('routing', $routing);
  		$acteurs = VracClient::getInstance()->getActeurs();
		foreach ($acteurs as $acteur) {
			if ($email = $vrac->get($acteur)->email) {
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
				$url['contact'] = $routing->generate('contact', array(), true);
				$url['home'] = $routing->generate('homepage', array(), true);
				if ($etablissement->compte) {
					if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
						if ($compte->statut == _Compte::STATUT_ARCHIVE) {
							if ($interpro->email_contrat_vrac) {
								Email::getInstance($contextInstance)->vracExpirationAnnulationContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
							}
						}
					}
				}
				Email::getInstance($contextInstance)->vracExpirationAnnulationContrat($vrac, $etablissement, $email, $acteur, $url);
			}
		}
  }
}
