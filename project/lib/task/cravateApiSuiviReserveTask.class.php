<?php
class cravateApiSuiviReserveTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, 'Identifiant operateur'),
    	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'cravate-api';
        $this->name = 'suivi-reserve';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $result = [
            'RAISON_SOCIALE' => null,
            'CVI' => null,
            'NUMCIVP' => null,
            'EMAIL' => null,
            'TELEPHONE' => null
        ];
        $contrats = [];

        $etablissement = EtablissementClient::getInstance()->find($arguments['identifiant']);
        if (!$etablissement) {
            $etablissements = EtablissementIdentifiantView::getInstance()->findByIdentifiant($arguments['identifiant'])->rows;
            if (count($etablissements) == 1) {
                $etablissement = $etablissements[0];
            }
        }
        if ($etablissement) {
            $result['RAISON_SOCIALE'] = $etablissement->raison_sociale;
            $result['CVI'] = $etablissement->cvi;
            $result['NUMCIVP'] = $etablissement->identifiant;
            $result['EMAIL'] = $etablissement->email;
            $result['TELEPHONE'] = $etablissement->email;
        }

        $vracs = VracAllView::getInstance()->findByEtablissement($etablissement->identifiant);
        foreach ($vracs->rows as $c) {
            if ($c->key[VracAllView::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_NONSOLDE) {
                if (!isset($contrats[$c->key[VracAllView::VRAC_VIEW_PRODUIT]])) {
                    $contrats[$c->key[VracAllView::VRAC_VIEW_PRODUIT]] = [];
                }
                $vrac = VracClient::getInstance()->find($c->key[VracAllView::VRAC_VIEW_ID]);
                if ($vrac->type_transaction != 'vrac') {
                    continue;
                }
                $contrats[$c->key[VracAllView::VRAC_VIEW_PRODUIT]][$vrac->_id] = [
                    'NUMVISA' => $vrac->numero_contrat,
                    'PRODUIT_LIBELLE' => trim($vrac->produit_libelle),
                    'LABELS' => $vrac->labels_libelle,
                    'MILLESIME' => $vrac->millesime,
                    'VOLUME' => $vrac->volume_propose,
                    'URL' => ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', ['sf_subject' => $vrac, 'etablissement' => $etablissement->identifiant], true)
                ];
           }

        }

        echo json_encode(['form' => $result, 'CONTRAT_VRAC' => $contrats], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

}
