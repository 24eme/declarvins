<?php

require_once(dirname(__FILE__).'/../bootstrap/unit.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('declarvin', 'test', true);
$databaseManager = new sfDatabaseManager($configuration);
sfContext::createInstance($configuration);

function loadDoc($file, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
    $json = json_decode(file_get_contents($file));
    $doc = acCouchdbManager::getClient()->find($json->_id, acCouchdbClient::HYDRATE_JSON);
    if($doc) {
        acCouchdbManager::getClient()->deleteDoc($doc);
    }
    acCouchdbManager::getClient()->storeDoc($json);

    return acCouchdbManager::getClient()->find($json->_id, $hydrate);
}

loadDoc(sfConfig::get('sf_test_dir')."/data/declarvins/CURRENT.json");
loadDoc(sfConfig::get('sf_test_dir')."/data/declarvins/CONFIGURATION.json");
loadDoc(sfConfig::get('sf_test_dir')."/data/declarvins/CONFIGURATION-PRODUITS-IVSE.json");

$viti = loadDoc(sfConfig::get('sf_test_dir')."/data/declarvins/ETABLISSEMENT.json");
$societeViti = loadDoc(sfConfig::get('sf_test_dir')."/data/declarvins/SOCIETE.json");

var_dump($societeViti->getRegionViticole());

$t = new lime_test(2);

$t->comment("Chargement de la DRM");

$drm = loadDoc(sfConfig::get('sf_test_dir')."/data/declarvins/DRM.json", acCouchdbClient::HYDRATE_JSON);

$t->comment("Création d'une facture à partir d'une DRM d'une société");

$paramFacturation =  array(
    "modele" => "DRM",
    "date_facturation" => date('Y').'-08-01',
    "date_mouvement" => null,
    "type_document" => GenerationClient::TYPE_DOCUMENT_FACTURES,
    "message_communication" => null,
    "seuil" => null,
);

$mouvementsFacture = array($societeViti->identifiant => FactureClient::getInstance()->getFacturationForSociete($societeViti));

$mouvementsFacture = FactureClient::getInstance()->filterWithParameters($mouvementsFacture, $paramFacturation);

$t->is(count($mouvementsFacture[$societeViti->identifiant]), 1, "La société à un mouvement facturable");

$facture = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation);

$facture->save();
$t->ok($facture, "La facture est créée");