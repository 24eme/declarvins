<?php

class vracRelanceTask extends sfBaseTask
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
      new sfCommandOption('sendmail', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 1),
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

    $this->routing = clone ProjectConfiguration::getAppRouting();
  	$this->contextInstance = sfContext::createInstance($this->configuration);
    $this->contextInstance->set('routing', $this->routing);

    $vracs = array_merge(VracHistoryView::getInstance()->findLast()->rows, VracHistoryView::getInstance()->findLast(1)->rows);
    foreach ($vracs as $vrac) {
    	$values = $vrac->value;
        if (!$values[VracHistoryView::VRAC_VIEW_STATUT]) continue;
    	$this->sendRelance($values, $options['sendmail']);
    }
  }

  protected function sendRelance($values, $sendmail) {
  	$today = new DateTime();
	$datesaisie = new DateTime($values[VracHistoryView::VRAC_VIEW_DATESAISIE]);
	$interval = $today->diff($datesaisie);
	$ecart = $interval->format('%a');
  	if ($ecart >= self::NB_JOUR_RELANCE && !$values[VracHistoryView::VRAC_VIEW_DATERELANCE]) {
  		$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  		$vrac->date_relance = date('c');
  		$vrac->save(false);
        try {
      		if ($sendmail && $values[VracHistoryView::VRAC_VIEW_ACHETEURID] && !$values[VracHistoryView::VRAC_VIEW_ACHETEURVAL]) {
      			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_ACHETEURID], VracClient::VRAC_TYPE_ACHETEUR);
      		}
      		if ($sendmail && $values[VracHistoryView::VRAC_VIEW_MANDATAIREID] && !$values[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]) {
      			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_MANDATAIREID], VracClient::VRAC_TYPE_COURTIER);
      		}
      		if ($sendmail && $values[VracHistoryView::VRAC_VIEW_VENDEURID] && !$values[VracHistoryView::VRAC_VIEW_VENDEURVAL]) {
      			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_VENDEURID], VracClient::VRAC_TYPE_VENDEUR);
      		}
        } catch(Exception $e) {
            $this->logSection('vrac-relance', 'Relance échouée pour le contrat '.$vrac->_id);
            return;
        }
  		$this->logSection('vrac-relance', 'Relance envoyée pour le contrat '.$vrac->_id);
  	}
  }

  protected function sendEmail($vrac, $identifiant, $acteur) {

  	$etablissement = EtablissementClient::getInstance()->find($identifiant);
	$compte = ($etablissement)? $etablissement->getCompteObject() : null;

  	$url['contact'] = $this->routing->generate('contact', array(), true);
  	$url['home'] = $this->routing->generate('homepage', array(), true);
  	$url['lien'] = $this->routing->generate('vrac_validation', array('sf_subject' => $vrac, 'etablissement' => $etablissement, 'acteur' => $acteur), true);

	if ($compte && $compte->email) {
		if ($compte->statut == _Compte::STATUT_ARCHIVE) {
			if ($interpro->email_contrat_vrac) {
				Email::getInstance($this->contextInstance)->vracRelanceContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
			}
		} else {
			Email::getInstance($this->contextInstance)->vracRelanceContrat($vrac, $etablissement, $compte->email, $acteur, $url);
		}
	} else {
		Email::getInstance($this->contextInstance)->vracRelanceContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur, $url);
	}
  }
}
