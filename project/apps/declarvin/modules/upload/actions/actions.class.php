<?php

/**
 * import actions.
 *
 * @package    declarvin
 * @subpackage import
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class uploadActions extends sfActions
{
  public function executeFile(sfWebRequest $request)
  {
    $this->formUploadCsv = new UploadCSVForm();
    
    if ($request->isMethod('post')) {
      $this->formUploadCsv->bind($request->getParameter($this->formUploadCsv->getName()), $request->getFiles($this->formUploadCsv->getName()));
      if ($this->formUploadCsv->isValid()) {
	return $this->redirect('upload/csvView?md5=' . $this->formUploadCsv->getValue('file')->getMd5());
      }
    }
  }

  private function verifyCsvLine($detail, $line) {
    if ($line[DRM::CSV_COL_DETAIL_ENTREES]*1 != $detail->total_entrees)
      throw new sfException("la somme des entrees (".$detail->total_entrees.") n'est pas en accord avec les informations du csv (".$line[14].")");
    if ($line[DRM::CSV_COL_DETAIL_SORTIES]*1 != $detail->total_sorties)
      throw new sfException("la somme des sorties n'est pas en accord avec les informations du csv");
    if ($line[DRM::CSV_COL_DETAIL_TOTAL]*1 != $detail->total)
      throw new sfException("le total n'est pas en accord avec les informations du csv");
    if ($line[DRM::CSV_COL_DETAIL_TOTAL_DEBUT_MOIS]*1 != $detail->total_debut_mois*1)
      throw new sfException("le total début de mois n'est pas en accord avec les informations historiques");
  }

  public function executeCsvView(sfWebRequest $request) 
  {
    $this->response->setContentType('text/plain');

    $md5 = $request->getParameter('md5');
    set_time_limit(600);
    $this->csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
    $config = ConfigurationClient::getCurrent();
    $drm = null;
    $errors = array();
    $numline = 0;
    foreach ($this->csv->getCsv() as $line) {
      $numline++;
      if ($line[DRM::CSV_COL_TYPE] != 'DETAIL')
	continue;
      try {
	if (!$drm)
	  $drm = DRMClient::getInstance()->retrieveOrCreateByIdentifiantAndCampagne('9223700100', $line[DRM::CSV_COL_DETAIL_ANNEE], $line[DRM::CSV_COL_DETAIL_MOIS]);
	$hash = $config->identifyProduct($line[DRM::CSV_COL_DETAIL_CERTIFICATION], 
					 $line[DRM::CSV_COL_DETAIL_APPELLATION], 
					 $line[DRM::CSV_COL_DETAIL_LIEU], 
					 $line[DRM::CSV_COL_DETAIL_COULEUR], 
					 $line[DRM::CSV_COL_DETAIL_CEPAGE], 
					 $line[DRM::CSV_COL_DETAIL_MILLESIME]);
	$detail = $drm->addProduit($hash, 
				   $config->identifyLabels($line[DRM::CSV_COL_DETAIL_LABELS]))->getDetail();
	if ($line[DRM::CSV_COL_DETAIL_MENTION])
	  $detail->label_supplementaire = $line[DRM::CSV_COL_DETAIL_MENTION] * 1;
	$detail->entrees->nouveau = $line[DRM::CSV_COL_DETAIL_ENTREES_NOUVEAU] *1 ;
	$detail->entrees->repli = $line[DRM::CSV_COL_DETAIL_ENTREES_REPLI] * 1 ;
	$detail->entrees->declassement = $line[DRM::CSV_COL_DETAIL_ENTREES_DECLASSEMENT] *1;
	$detail->entrees->mouvement = $line[DRM::CSV_COL_DETAIL_ENTREES_MOUVEMENT] *1;
	$detail->entrees->crd = $line[DRM::CSV_COL_DETAIL_ENTREES_CRD] *1;
	$detail->sorties->vrac = $line[DRM::CSV_COL_DETAIL_SORTIES_VRAC] *1;
	$detail->sorties->export = $line[DRM::CSV_COL_DETAIL_SORTIES_EXPORT] *1;
	$detail->sorties->factures = $line[DRM::CSV_COL_DETAIL_SORTIES_FACTURES] *1;
	$detail->sorties->crd = $line[DRM::CSV_COL_DETAIL_SORTIES_CRD] *1;
	$detail->sorties->consommation = $line[DRM::CSV_COL_DETAIL_SORTIES_CONSOMMATION] *1;
	$detail->sorties->pertes = $line[DRM::CSV_COL_DETAIL_SORTIES_PERTES] *1;
	$detail->sorties->declassement = $line[DRM::CSV_COL_DETAIL_SORTIES_DECLASSEMENT] *1;
	$detail->sorties->repli = $line[DRM::CSV_COL_DETAIL_SORTIES_REPLI] *1;
	$detail->sorties->mouvement = $line[DRM::CSV_COL_DETAIL_SORTIES_MOUVEMENT] *1;
	$detail->sorties->lies = $line[DRM::CSV_COL_DETAIL_SORTIES_LIES] *1;
	$detail->stocks_fin->bloque = $line[DRM::CSV_COL_DETAIL_STOCKFIN_BLOQUE] *1;
	$detail->stocks_fin->warrante = $line[DRM::CSV_COL_DETAIL_STOCKFIN_WARRANTE] *1;
	$detail->stocks_fin->instance = $line[DRM::CSV_COL_DETAIL_STOCKFIN_INSTANCE] *1;
	$drm->update();
	$this->verifyCsvLine($detail, $line);
      }catch(sfException $e){
	$errors[] = array('line'=> $numline, 'message'=>$e->getMessage());
      }
    }
    $this->iddrm = null;
    if (!count($errors)) {
      $drm->save();
      $this->iddrm = $drm->_id;
    }
    $this->errors = $errors;
    $this->setLayout(false);
  }
}
