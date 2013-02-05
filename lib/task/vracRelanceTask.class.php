<?php

class vracRelanceTask extends sfBaseTask
{
	const NB_JOUR_RELANCE = 7;
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
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
    set_time_limit(600);
    
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
  		$vrac = null;
  		if ($values[VracHistoryView::VRAC_VIEW_ACHETEURID] && !$values[VracHistoryView::VRAC_VIEW_ACHETEURVAL]) {
  			if (!$vrac) {
  				$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  			}
  			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_ACHETEURID], VracClient::VRAC_TYPE_ACHETEUR);
  		}
  		if ($values[VracHistoryView::VRAC_VIEW_MANDATAIREID] && !$values[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]) {
  		  	if (!$vrac) {
  				$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  			}
  			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_MANDATAIREID], VracClient::VRAC_TYPE_COURTIER);
  		}
  		if ($values[VracHistoryView::VRAC_VIEW_VENDEURID] && !$values[VracHistoryView::VRAC_VIEW_VENDEURVAL]) {
  		  	if (!$vrac) {
  				$vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
  			}
  			$this->sendEmail($vrac, $values[VracHistoryView::VRAC_VIEW_VENDEURID], VracClient::VRAC_TYPE_VENDEUR);
  		}
  		if ($vrac) {
  			$vrac->date_relance = date('c');
  			$vrac->save();
  		}
  	}
  }
  
  protected function sendEmail($vrac, $identifiant, $acteur) {
  	$etablissement = EtablissementClient::getInstance()->find($identifiant);
  	if ($etablissement->compte) {
		if ($compte = _CompteClient::getInstance()->find($etablissement->compte)) {
			if ($compte->statut == _Compte::STATUT_ARCHIVE) {
				if ($interpro->email_contrat_vrac) {
					Email::getInstance()->vracRelanceContrat($vrac, $etablissement, $interpro->email_contrat_vrac, $acteur);
				}
			}
		}
	}
	Email::getInstance()->vracRelanceContrat($vrac, $etablissement, $etablissement->email, $acteur);
  }
}
