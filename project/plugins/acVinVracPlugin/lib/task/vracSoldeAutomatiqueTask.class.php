<?php
class vracSoldeAutomatiqueTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace        = 'vrac';
        $this->name             = 'solde-automatique';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [importEtablissement|INFO] task does things.
Call it with:
  [php symfony vrac:solde-automatique|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        set_time_limit(0);

        $vracs = VracHistoryView::getInstance()->findByStatut(VracClient::STATUS_CONTRAT_NONSOLDE)->rows;
        $cm = new CampagneManager('08-01');
        $campagneRaisinMout = substr($cm->getCampagneByDate(date('Y-m-d', strtotime('-1 year'))), 0, 4);
        $campagneVrac = substr($cm->getCampagneByDate(date('Y-m-d', strtotime('-3 year'))), 0, 4);
        foreach ($vracs as $vrac) {
    	    $values = $vrac->value;
            $dateValidation = $values[VracHistoryView::VRAC_VIEW_DATESAISIE];
            if (!empty($values[VracHistoryView::VRAC_VIEW_ACHETEURVAL]) && $values[VracHistoryView::VRAC_VIEW_ACHETEURVAL] > $dateValidation) {
                $dateValidation = $values[VracHistoryView::VRAC_VIEW_ACHETEURVAL];
            }
            if (!empty($values[VracHistoryView::VRAC_VIEW_MANDATAIREVAL]) && $values[VracHistoryView::VRAC_VIEW_MANDATAIREVAL] > $dateValidation) {
                $dateValidation = $values[VracHistoryView::VRAC_VIEW_MANDATAIREVAL];
            }
            if (!empty($values[VracHistoryView::VRAC_VIEW_VENDEURVAL]) && $values[VracHistoryView::VRAC_VIEW_VENDEURVAL] > $dateValidation) {
                $dateValidation = $values[VracHistoryView::VRAC_VIEW_VENDEURVAL];
            }
            $year = substr($cm->getCampagneByDate($dateValidation), 0, 4);
            if (in_array($values[VracHistoryView::VRAC_VIEW_TYPEPRODUIT], [VracClient::TYPE_TRANSACTION_RAISINS, VracClient::TYPE_TRANSACTION_MOUTS])) {
                $campagne = $campagneRaisinMout;
            } else {
                $campagne = $campagneVrac;
            }
            if ($year < $campagne) {
                $vrac = VracClient::getInstance()->find($values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]);
                $vrac->valide->statut = VracClient::STATUS_CONTRAT_SOLDE;
                $vrac->save(false);
                echo $values[VracHistoryView::VRAC_VIEW_NUMCONTRAT]." soldé avec succes\n";
            }
        }
    }
}
