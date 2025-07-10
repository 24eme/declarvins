<?php

class vracDeterminationPrixTask extends sfBaseTask
{
	const NB_JOUR_RELANCE = 7;
    protected $routing;
    private $contextInstance;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('vrac_id', null, sfCommandOption::PARAMETER_REQUIRED, 'ID of a single vrac', null),
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

    $this->routing = clone ProjectConfiguration::getAppRouting();
  	$this->contextInstance = sfContext::createInstance($this->configuration);
    $this->contextInstance->set('routing', $this->routing);

    $cm = new CampagneManager('08-01');
    $from = $cm->getDateDebutByCampagne($cm->getPrevious($cm->getCurrent()));
    if (! $options['vrac_id']) {
        $vracs = VracDeterminationprixView::getInstance()->findLast()->rows;
    } else {
        $vracs = [(object) ['id' => $options['vrac_id']]];
    }
    foreach ($vracs as $row) {
        if ($vrac = VracClient::getInstance()->find($row->id)) {
            if ($vrac->type_prix == 'definitif') {
                continue;
            }
            if ($vrac->interpro == 'INTERPRO-CIVP') {
                continue;
            }
            if ($vrac->valide->statut == VracClient::STATUS_CONTRAT_SOLDE || $vrac->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) {
                $current_minus_7 = null;
                if (! $vrac->date_seconde_relance && $vrac->date_relance && $vrac->date_relance > $vrac->valide->date_validation) {
                    $date_relance = new DateTime($vrac->date_relance);
                    $current_date = new DateTime();
                    $current_minus_7 = $current_date->sub(new DateInterval('P7D'));
                }
                if (!$vrac->date_relance || ($vrac->date_relance < $vrac->valide->date_validation) || ($date_relance->format('Y-m-d') == $current_minus_7->format('Y-m-d'))) {
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
  	$etablissement = EtablissementClient::getInstance()->find($identifiant);


  	$url['contact'] = $this->routing->generate('contact', array(), true);
  	$url['home'] = $this->routing->generate('homepage', array(), true);

  	$compte = ($etablissement)? $etablissement->getCompteObject() : null;
  	if ($compte && $compte->email) {
  	    if ($compte->statut == _Compte::STATUT_ARCHIVE) {
  	        if ($interpro->email_contrat_vrac) {
  	            Email::getInstance($this->contextInstance)->vracDeterminationPrix($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
  	        }
  	    } else {
  	        Email::getInstance($this->contextInstance)->vracDeterminationPrix($vrac, $etablissement, $compte->email, $acteur, $url);
  	    }
  	} else {
  	    Email::getInstance($this->contextInstance)->vracDeterminationPrix($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
  	}

  }
}
