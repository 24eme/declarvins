<?php

class vracRelanceTask extends sfBaseTask
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
    $this->name             = 'relance';
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
    
    $vracs = VracHistoryView::getInstance()->findLast();
    foreach ($vracs->rows as $vrac) {
    	$values = $vrac->value;
    	$this->sendRelance($values);		
    }
  }
  
  protected function sendRelance($values) {
  	$today = new DateTime();
	$datesaisie = new DateTime($values[VracHistoryView::VRAC_VIEW_DATESAISIE]);
	$interval = $today->diff($datesaisie);
	$ecart = $interval->format('%a');
  	if ($ecart >= self::NB_JOUR_RELANCE && !$values[VracHistoryView::VRAC_VIEW_DATERELANCE]) {
  		$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  		if ($values[VracHistoryView::VRAC_VIEW_ACHETEURID] && !$values[VracHistoryView::VRAC_VIEW_ACHETEURVAL]) {
  			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_ACHETEURID], VracClient::VRAC_TYPE_ACHETEUR);
  		}
  		if ($values[VracHistoryView::VRAC_VIEW_MANDATAIREID] && !$values[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]) {
  			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_MANDATAIREID], VracClient::VRAC_TYPE_COURTIER);
  		}
  		if ($values[VracHistoryView::VRAC_VIEW_VENDEURID] && !$values[VracHistoryView::VRAC_VIEW_VENDEURVAL]) {
  			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_VENDEURID], VracClient::VRAC_TYPE_VENDEUR);
  		}
  		$vrac->date_relance = date('c');
  		$vrac->save();
  		$this->logSection('vrac-relance', 'Relance envoyée pour le contrat '.$vrac->_id);
  	}
  }
  
  protected function sendEmail($vrac, $identifiant, $acteur) {
  	
  	$routing = clone ProjectConfiguration::getAppRouting();
	$contextInstance = sfContext::createInstance($this->configuration);
    $contextInstance->set('routing', $routing);
    
  	$etablissement = EtablissementClient::getInstance()->find($identifiant);


  	$url['contact'] = $routing->generate('contact', array(), true);
  	$url['home'] = $routing->generate('homepage', array(), true);
  	$url['lien'] = $routing->generate('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur), true);

  	if ($etablissement->compte) {
		if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
			if ($compte->statut == _Compte::STATUT_ARCHIVE) {
				if ($interpro->email_contrat_vrac) {
					Email::getInstance($contextInstance)->vracRelanceContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
				}
			}
		}
	}
	if ($etablissement->email) {
		try {
			Email::getInstance($contextInstance)->vracRelanceContrat($vrac, $etablissement, $etablissement->email, $acteur, $url);
		} catch (Exception $e) {
			return;
		}
	}
  }
}
