<?php

class vracExpirationTask extends sfBaseTask
{
	const NB_JOUR_EXPIRATION = 10;
    private $routing;
    private $contextInstance;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('sendmail', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 1),
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
    set_time_limit(0);

    $this->routing = clone ProjectConfiguration::getAppRouting();
  	$this->contextInstance = sfContext::createInstance($this->configuration);
    $this->contextInstance->set('routing', $this->routing);

    $vracs = array_merge(VracHistoryView::getInstance()->findLast()->rows, VracHistoryView::getInstance()->findLast(1)->rows);
    foreach ($vracs as $vrac) {
    	$values = $vrac->value;
        if (!$values[VracHistoryView::VRAC_VIEW_STATUT]) continue;
		if ($values[VracHistoryView::VRAC_VIEW_TYPEPRODUIT] != 'vrac') continue;
    	$this->sendExpiration($values, $options['sendmail']);
    }
  }

  protected function sendExpiration($values, $sendmail) {
  	$today = new DateTime();
	$datesaisie = new DateTime($values[VracHistoryView::VRAC_VIEW_DATESAISIE]);
	$interval = $today->diff($datesaisie);
	$ecart = $interval->format('%a');
  	if ($ecart >= self::NB_JOUR_EXPIRATION && $values[VracHistoryView::VRAC_VIEW_DATERELANCE]) {
  		$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  		$acteurs = VracClient::getInstance()->getActeurs();
		foreach ($acteurs as $acteur) {
			if (!$vrac->get($acteur.'_identifiant')) {
				continue;
			}
			$etablissement = EtablissementClient::getInstance()->find($vrac->get($acteur.'_identifiant'));
			$compte = ($etablissement)? $etablissement->getCompteObject() : null;
			$url['contact'] = $this->routing->generate('contact', array(), true);
			$url['home'] = $this->routing->generate('homepage', array(), true);
            try {
    			if ($sendmail && $compte && $compte->email) {
    		  	    if ($compte->statut == _Compte::STATUT_ARCHIVE) {
    		  	        if ($interpro->email_contrat_vrac) {
    		  	            Email::getInstance($this->contextInstance)->vracExpirationContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
    		  	        }
    		  	    } else {
    		  	        Email::getInstance($this->contextInstance)->vracExpirationContrat($vrac, $etablissement, $compte->email, $acteur, $url);
    		  	    }
    		  	} elseif ($sendmail) {
    		  	    Email::getInstance($this->contextInstance)->vracExpirationContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
    		  	}
            } catch(Exception $e) {
                $this->logSection('vrac-expiration', 'Envoi email expiration échoué pour le contrat '.$vrac->_id);
            }
		}
  		$this->logSection('vrac-expiration', 'Expiration du contrat '.$vrac->_id);
  		$vrac->delete();
  	}
  }
}
