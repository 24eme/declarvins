<?php

class ExportEdiCSVTask extends sfBaseTask
{
    public static $EXCEPTION_PRESENCE_PRODUITS_EDIDRM = [
        'declaration/certifications/AOP/genres/TRANQ/appellations/BAN'
    ];
    protected function configure()
    {
        $this->addArguments(array(
            new sfCommandArgument('interpro', sfCommandArgument::REQUIRED, "Interpro"),
            new sfCommandArgument('type', sfCommandArgument::REQUIRED, "Type"),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('fromdate', null, sfCommandOption::PARAMETER_OPTIONAL, 'From date', null),
        ));

        $this->namespace        = 'export';
        $this->name             = 'edi';
        $this->briefDescription = '';
        $this->detailedDescription = '';

    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $interpro = 'INTERPRO-'.str_replace('INTERPRO-', '', strtoupper($arguments['interpro']));

        if ($arguments['type'] == 'vrac') {
            $configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
            if ($d = $options['fromdate']) {
                $dateForView = new DateTime($date);
                $items = VracDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows;
            } else {
                $items = VracDateView::getInstance()->findByInterpro($interpro)->rows;
            }
            foreach ($items as $item) {
      			if ($item->value[VracDateView::VALUE_MODE_SAISIE] == 'EDI') continue;
      			$item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]));
      			$item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]));
      			$item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
      			$item->value[VracDateView::VALUE_PRIX_UNITAIRE] = round($item->value[VracDateView::VALUE_PRIX_UNITAIRE], 2);
      			$item->value[VracDateView::VALUE_PRIX_TOTAL] = round($item->value[VracDateView::VALUE_PRIX_TOTAL], 2);
                echo str_replace(array(chr(10), chr(13)), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)))."\n";
      		}
        }

        if ($arguments['type'] == 'drm') {
    		$numberValues = DRMDateView::numberValues();
            $famille = null;
            if ($d = $options['fromdate']) {
                $dateForView = new DateTime($d);
                $items = DRMDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows;
            } else {
                $items = DRMDateView::getInstance()->findByInterpro($interpro)->rows;
            }
            echo implode(';', $this->getEntetesDrm())."\n";
            foreach ($items as $item) {
                if ($item->value[DRMDateView::VALUE_TYPE] != 'DETAIL') {
                    $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT] = null;
                }
                $item->value[DRMDateView::VALUE_DETAIL_HASH_PRODUIT_GENERATED] = '';
                if ($interpro == 'INTERPRO-IR' && $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT]) {
                    $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] = $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] + $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT];
                }
    			foreach ($numberValues as $val) {
    				if ($item->value[$val]) {
    					$item->value[$val] = number_format($item->value[$val], 5, '.', '');
    				}
    			}
      			if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && (is_null($item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX]) || $item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX] < 0 || !$item->value[DRMDateView::VALUE_DETAIL_CVO_CODE])) {
                    $isExceptionProduit = false;
                    foreach (self::$EXCEPTION_PRESENCE_PRODUITS_EDIDRM as $hash) {
                        if (strpos($item->key[DRMDateView::KEY_DETAIL_HASH], $hash) !== false) {
                            $isExceptionProduit = true;
                            break;
                        }
                    }
                    if (!$isExceptionProduit) {
                        continue;
                    }
      			}
      			if ($interpro == 'INTERPRO-CIVP' && !$famille && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != 'producteur') {
      				continue;
      			}
      			if ($interpro == 'INTERPRO-IVSE' && !$famille && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != 'producteur' && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_SOUSFAMILLE] != 'vinificateur') {
      				continue;
      			}
      			if (($interpro == 'INTERPRO-CIVP' || $interpro == 'INTERPRO-IVSE') && $famille && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != $famille) {
      			    continue;
      			}
                if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && !$item->value[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS] && !$item->value[DRMDateView::VALUE_DETAIL_ENTREES] && !$item->value[DRMDateView::VALUE_DETAIL_SORTIES]) {
                    continue;
                }
                echo str_replace(array(chr(10), chr(13)), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)))."\n";
      		}
        }

    }

    private function getEntetesDrm() {
    return [
    "#type ligne",
    "identifiant declarvins du déclarant",
    "raison sociale du déclarant",
    "année période drm",
    "mois période drm",
    "version drm",
    "champ vide",
    "champ vide",
    "champ vide",
    "nom de la certification du vin",
    "code de la certification du vin",
    "nom du genre du vin",
    "code du genre du vin",
    "nom de l'appellation du vin",
    "code de l'appellation du vin",
    "nom du lieu du vin",
    "code du lieu du vin",
    "nom de la couleur du vin",
    "code de la couleur du vin",
    "nom du cépage du vin",
    "code du cépage du vin",
    "millésime du vin",
    "champ vide",
    "nom du label",
    "code du label",
    "label complementaire",
    "total debut mois",
    "stocks debut bloque",
    "stocks debut warrante",
    "stocks debut instance",
    "stocks debut commercialisable",
    "total entrees",
    "entrees achat",
    "entrees recolte",
    "entrees repli",
    "entrees declassement",
    "entrees mouvement",
    "entrees crd",
    "total sorties",
    "sorties vrac",
    "sorties export",
    "sorties factures",
    "sorties crd",
    "sorties consommation",
    "sorties pertes",
    "sorties declassement",
    "sorties repli",
    "sorties mouvement",
    "sorties distillation",
    "sorties lies",
    "total",
    "stocks fin bloque",
    "stocks fin warrante",
    "stocks fin instance",
    "stocks fin commercialisable",
    "date saisie drm",
    "date signature drm",
    "mode saisie drm",
    "code cvo",
    "taux cvo",
    "volume cvo",
    "montant cvo",
    "campagne drm",
    "identifiant drm historique",
    "identifiant drm ivse",
    "numéro visa contrat si type CONTRAT",
    "volume sortie contrat si type CONTRAT",
    "commentaire drm",
    "drm referente",
    "drm a contrats manquants",
    "drm a igp manquants",
    "observation mouvement",
    "entrees vci",
    "famille du declarant",
    "sous famille du declarant",
    "code inao du vin",
    "libelle fiscal du vin",
    "tav",
    "entrees manipulation",
    "entrees embouteillage",
    "entrees travail",
    "entrees distillation",
    "entrees excedent",
    "sorties crd_acquittes",
    "sorties mutages",
    "sorties vci",
    "sorties autres_interne",
    "sorties embouteillage",
    "sorties travail",
    "sorties distillation",
    "total_debut_mois acquitté",
    "entrees achat acquitté",
    "entrees autres acquitté",
    "sorties crd acquitté",
    "sorties replacement acquitté",
    "sorties autres acquitté",
    "total acquitté",
    "montant avoir",
    "sorties vrac export",
    "hash produit"
    ];
    }

}
