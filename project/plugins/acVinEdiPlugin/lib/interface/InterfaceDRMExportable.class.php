<?php
interface InterfaceDRMExportable
{
	public function getExportableProduits();
	public function hasExportableProduitsAcquittes();
	public function getExportableVracs();
	public function getExportableCrds();
	public function getExportableAnnexes();
}