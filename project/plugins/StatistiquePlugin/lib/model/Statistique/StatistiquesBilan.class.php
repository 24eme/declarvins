<?php

class StatistiquesBilan
{
	const KEY_DRM_LIST = '_drms';
	
	protected $interpro;
	protected $campagne;
	protected $drms;
	protected $etablissements;
	protected $periodes;
	
	public function __construct($interpro, $campagne)
	{
		$this->interpro = $interpro;
		$this->campagne = $campagne;
		$this->buildPeriodes();
		$this->drms = array();
		$this->etablissements = array();
		$datas = StatistiquesBilanView::getInstance()->findDrmByCampagne($this->interpro, $this->campagne)->rows;
		$this->setInformations($datas);
	}
	
	private function buildPeriodes()
	{
		$this->periodes = array();
		$stopPeriode = date('Y-m', strtotime('-1 month'));
		$months = array('08', '09', '10', '11', '12', '01', '02', '03', '04', '05', '06', '07');
		$years = explode('-', $this->campagne);
		foreach ($months as $month) {
			if ($month < 8) {
				$periode = $years[1].'-'.$month;
			} else {
				$periode = $years[0].'-'.$month;
			}
			if ($periode > $stopPeriode) {
				break;
			}
			$this->periodes[] = $periode;
		}
		return $this->periodes;
	}
	
	private function setInformations($datas)
	{
		foreach ($datas as $data) {
			$etablissement = $data->key[StatistiquesBilanView::KEY_ETABLISSEMENT];
			$periode = $data->key[StatistiquesBilanView::KEY_PERIODE];
			if (!isset($this->etablissements[$etablissement])) {
				$this->setEtablissementInformations($etablissement, $data->value);
			}
			$this->setDRMInformations($etablissement, $periode, $data->value);
		}
	}
	
	private function setEtablissementInformations($etablissement, $dataValues)
	{
		$this->etablissements[$etablissement] = array();
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_SIRET] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_SIRET];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_CNI] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_CNI];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_NUM_ACCISES] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_NUM_ACCISES];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_NUM_TVA_INTRACOMMUNAUTAIRE] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_NUM_TVA_INTRACOMMUNAUTAIRE];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS];
		$this->etablissements[$etablissement][StatistiquesBilanView::VALUE_ETABLISSEMENT_SERVICE_DOUANE] = $dataValues[StatistiquesBilanView::VALUE_ETABLISSEMENT_SERVICE_DOUANE];
	}
	
	private function setDRMInformations($etablissement, $periode, $dataValues)
	{
		$this->drms[$etablissement][$periode] = array();
		$this->drms[$etablissement][$periode][StatistiquesBilanView::VALUE_DRM_TOTAL_FIN_DE_MOIS] = $dataValues[StatistiquesBilanView::VALUE_DRM_TOTAL_FIN_DE_MOIS];
		$this->drms[$etablissement][$periode][StatistiquesBilanView::VALUE_DRM_DATE_SAISIE] = $dataValues[StatistiquesBilanView::VALUE_DRM_DATE_SAISIE];
	}
	
	public function getEtablissementsInformations()
	{
		return $this->etablissements;
	}
	
	public function getDRMsInformations()
	{
		return $this->drms;
	}
	
	public function getPeriodes()
	{
		return $this->periodes;
	}
}  