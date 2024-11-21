<?php

class vracExpirationAnnulationTask extends sfBaseTask
{
	const NB_JOUR_EXPIRATION = 3;
    private $routing;
    private $contextInstance;

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

    $this->routing = clone ProjectConfiguration::getAppRouting();
  	$this->contextInstance = sfContext::createInstance($this->configuration);
    $this->contextInstance->set('routing', $this->routing);

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
  		$acteurs = VracClient::getInstance()->getActeurs();
		foreach ($acteurs as $acteur) {
				if (!$vrac->get($acteur.'_identifiant')) {
					continue;
				}
				$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
				$compte = ($etablissement)? $etablissement->getCompteObject() : null;
				$url['contact'] = $this->routing->generate('contact', array(), true);
				$url['home'] = $this->routing->generate('homepage', array(), true);

				if ($compte && $compte->email) {
		  	    if ($compte->statut == _Compte::STATUT_ARCHIVE) {
		  	        if ($interpro->email_contrat_vrac) {
		  	            Email::getInstance($this->contextInstance)->vracExpirationAnnulationContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
		  	        }
		  	    } else {
		  	        Email::getInstance($this->contextInstance)->vracExpirationAnnulationContrat($vrac, $etablissement, $compte->email, $acteur, $url);
		  	    }
		  	} else {
		  	    Email::getInstance($this->contextInstance)->vracExpirationAnnulationContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
		  	}

		}
  }
}
