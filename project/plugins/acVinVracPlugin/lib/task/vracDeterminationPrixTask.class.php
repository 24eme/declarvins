<?php

class vracDeterminationPrixTask extends sfBaseTask
{
	const NB_JOUR_RELANCE = 7;
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'vrac';
    $this->name             = 'determination-prix';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importEtablissement|INFO] task does things.
Call it with:

  [php symfony vracRelance|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    set_time_limit(0);
    
    $cm = new CampagneManager('08-01');
    $from = $cm->getDateDebutByCampagne($cm->getPrevious($cm->getCurrent()));
    $vracs = VracDeterminationprixView::getInstance()->findLast();
    foreach ($vracs->rows as $row) {
        if ($vrac = VracClient::getInstance()->find($row->id)) {
            if ($vrac->type_prix == 'definitif') {
                continue;
            }
            if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE || $vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) {
                $current_minus_7 = null;
                if (! $vrac->date_seconde_relance && $vrac->date_relance && $vrac->date_relance > $vrac->valide->date_validation) {
                    $date_relance = new DateTime($vrac->date_relance);
                    $current_date = new DateTime();
                    $current_minus_7 = $current_date->sub(new DateInterval('P7D'));
                }
                print_r($vrac);
                if (!$vrac->date_relance || ($vrac->date_relance < $vrac->valide->date_validation) || ($date_relance == $current_minus_7)) {
                    $this->sendEmail($vrac, $vrac->acheteur_identifiant, 'acheteur');
                    if (! $current_minus_7) {
                        $vrac->date_relance = date('c');
                    } else {
                        $vrac->date_seconde_relance = date('c');
                    }
                    $vrac->save();
                    $this->logSection('vrac-determination-prix', 'Relance envoyée pour le contrat '.$vrac->_id);
                }
            }
        }
    }
  }
  
  protected function sendEmail($vrac, $identifiant, $acteur) {
  	
  	$routing = clone ProjectConfiguration::getAppRouting();
	$contextInstance = sfContext::createInstance($this->configuration);
    $contextInstance->set('routing', $routing);
    
  	$etablissement = EtablissementClient::getInstance()->find($identifiant);


  	$url['contact'] = $routing->generate('contact', array(), true);
  	$url['home'] = $routing->generate('homepage', array(), true);
  	
  	$compte = ($etablissement)? $etablissement->getCompteObject() : null;
  	if ($compte && $compte->email) {
  	    if ($compte->statut == _Compte::STATUT_ARCHIVE) {
  	        if ($interpro->email_contrat_vrac) {
  	            Email::getInstance($contextInstance)->vracDeterminationPrix($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
  	        }
  	    } else {
  	        Email::getInstance($contextInstance)->vracDeterminationPrix($vrac, $etablissement, $compte->email, $acteur, $url);
  	    }
  	} else {
  	    Email::getInstance($contextInstance)->vracDeterminationPrix($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
  	}

  }
}
