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
                $vrac = VracClient::getInstance()->find($c->key[VracAllView::VRAC_VIEW_ID]);
                if ($vrac->type_transaction != 'vrac') {
                    continue;
                }
                if (!in_array($vrac->produit_detail->appellation->code, ['CP', 'CVP', 'CAP'])||$vrac->produit_detail->couleur->code != 2) {
                    continue;
                }
                $libelle = 'Contrat vrac '.$vrac->numero_contrat.' : '.trim($vrac->produit_libelle).' '.$vrac->labels_libelle.' '.$vrac->millesime.' '.number_format($vrac->volume_propose, 2, ',', ' ').' hl';
                $contrats[$libelle] = ProjectConfiguration::getAppRouting()->generate('vrac_visualisation', ['sf_subject' => $vrac, 'etablissement' => $etablissement->identifiant], true);
           }

        }

        echo json_encode(['form' => $result, 'annexes' => ["Contrat_vente_en_vrac" => $contrats]], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

}
