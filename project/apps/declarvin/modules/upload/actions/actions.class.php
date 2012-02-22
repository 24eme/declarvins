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

  public function executeCsvView(sfWebRequest $request) 
  {
    $md5 = $request->getParameter('md5');
    set_time_limit(600);
    $this->csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
    $config = ConfigurationClient::getCurrent();
    $drm = null;
    foreach ($this->csv->getCsv() as $line) {
      if (!$drm)
	$drm = new DRM('DRM-9223700100-'.$line[3].'-'.$line[4]);
      $res = $config->identifyProduct($line[5], $line[6], $line[7], $line[8], $line[9], $line[10]);
      $detail = $drm->addProduit($res['hash'], $config->identifyLabels($line[11]));
    }
    exit;
  }
}
