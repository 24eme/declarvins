<?php
interface InterfaceDRMExportable
{
	public function getExportableProduits();
	public function hasExportableProduitsAcquittes();
	public function getExportableVracs();
	public function getExportableCrds();
	public function getExportableAnnexes();
	public function getExportableObservations();
	public function getExportableStatistiquesEuropeennes();
	public function getExportableSucre();
	public function getExportableRna();
	public function getExportableDocuments();
	public function getExportableDeclarantInformations();
	public function getExportableCategoriesMouvements();
	public function getExportableLibelleMvt($key);
	public function getExportableCountryList();
}