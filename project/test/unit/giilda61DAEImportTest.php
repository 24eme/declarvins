<?php
require_once(dirname(__FILE__).'/../bootstrap/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('declarvin', 'prod', true);
$databaseManager = new sfDatabaseManager($configuration);

$t = new lime_test(5);

$t->comment("Création etablissement TEST1"); // ******************************************************
$e = EtablissementClient::getInstance()->retrieveOrCreateById('TEST1');
if ($e->isNew()) {
	$e->identifiant = "TEST1";
	$e->cvi = 1111111111;
	$e->siret = 11111111111111;
	$e->no_accises = 'FR111111E1111';
	$e->nom = 'Château TEST';
	$e->raison_sociale = 'Château TEST';
	$e->statut = 'ACTIF';
	$e->add('droits', array('dae'));
	$e->save();
}

$etablissement = EtablissementClient::getInstance()->findByIdentifiant('TEST1');
$t->ok($etablissement);

$csvTest = sfConfig::get('sf_data_dir').'/dae/import-test.csv';
$t->comment("Import DAE depuis $csvTest"); // ********************************************************

if (file_exists($csvTest)) {
	
	$start = microtime(true);
	
	$daeCsvEdi = new DAEImportCsvEdi($csvTest, $etablissement->identifiant, date('Y-m-d'));
	$daeCsvEdi->setForceEtablissement(true);
	
	$daeCsvEdi->checkCSV();
	
	$t->is($daeCsvEdi->getCsvDoc()->hasErreurs(), 0);
	
	if ($daeCsvEdi->getCsvDoc()->hasErreurs() == 0) {
		
		$nbImported = $daeCsvEdi->importCsv();
		
		$t->is($daeCsvEdi->getCsvDoc()->hasErreurs(), 0);
		
		foreach ($daeCsvEdi->getCsvDoc()->erreurs as $erreur) {
			echo $erreur->num_ligne.' '.$erreur->csv_erreur.' '.$erreur->diagnostic."\n";
		}
		
		$end = microtime(true);
		
		$t->comment("Import : execution time : ".gmdate("H:i:s", $end - $start));
		
		
		$t->comment("Export des DAE importés"); // ***************************************************
		
		$export = new DAEExportCsv();
		$csvExport = '';
		$i = 0;
		$nbTotal = count($daeCsvEdi->getCampagnes());
		foreach ($daeCsvEdi->getCampagnes() as $campagne) {
			$i++;
			$csvExport .= $export->exportOnlyDAEByEtablissementAndCampagne($etablissement->identifiant, $campagne, false);
			if ($i < $nbTotal) {
				$csvExport .= "\n";
			}
		}
		$nbExported = ($csvExport)? count(str_getcsv($csvExport, "\n")) : 0;
		
		$t->is($nbImported, $nbExported);
		
		
		$daes = DAEClient::getInstance()->findByIdentifiant('TEST1', acCouchdbClient::HYDRATE_JSON);
		$client = acCouchdbManager::getClient();
		foreach ($daes as $dae) {
			$client->deleteDoc($dae);
		}
	}
	
	if ($csvDoc = $daeCsvEdi->getCsvDoc()) {
		$csvDoc->delete();
	}
	
} else {
	$t->comment("/!\ Le fichier non trouvé, l'import / export des DAE n'a pas pu être testé");
}

$t->comment("Suppression etablissement TEST1"); // ****************************************************
$etablissement->delete();

$etablissement = EtablissementClient::getInstance()->findByIdentifiant('TEST1');

$t->ok(!$etablissement);
