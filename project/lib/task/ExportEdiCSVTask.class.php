<?php

class ExportEdiCSVTask extends sfBaseTask
{
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
            $conf = ConfigurationClient::getCurrent();
    		$numberValues = DRMDateView::numberValues();
            $famille = null;
            if ($d = $options['fromdate']) {
                $dateForView = new DateTime($d);
                $items = DRMDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows;
            } else {
                $items = DRMDateView::getInstance()->findByInterpro($interpro)->rows;
            }
            foreach ($items as $item) {
                if ($item->value[DRMDateView::VALUE_TYPE] != 'DETAIL') {
                    $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT] = null;
                }
                $libelle = '';
                $hash = substr($item->key[DRMDateView::KEY_DETAIL_HASH], 0, strpos($item->key[DRMDateView::KEY_DETAIL_HASH], '/details/'));
                if ($hash && ($confProduit = $conf->getConfigurationProduit($hash))) {
                    $libelle = trim($confProduit->getLibelleFormat(array(), "%format_libelle%"));
                    if ($item->value[DRMDateView::VALUE_LABELS_CODE]) {
                        $libelle .= ' '.str_replace('|', ', ', $item->value[DRMDateView::VALUE_LABELS_CODE]);
                    }
                }
                $item->value[DRMDateView::VALUE_DETAIL_HASH_PRODUIT_GENERATED] = ($libelle)? md5($libelle) : '';
                if ($interpro == 'INTERPRO-IR' && $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT]) {
                    $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] = $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] + $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT];
                }
    			foreach ($numberValues as $val) {
    				if ($item->value[$val]) {
    					$item->value[$val] = number_format($item->value[$val], 5, '.', '');
    				}
    			}
      			if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && (is_null($item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX]) || $item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX] < 0 || !$item->value[DRMDateView::VALUE_DETAIL_CVO_CODE])) {
      				continue;
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
                echo str_replace(array(chr(10), chr(13)), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)))."\n";
      		}
        }

    }

}
