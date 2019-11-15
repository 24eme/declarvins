<?php

class cielDrmCft extends sfBaseTask
{
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('target', sfCommandArgument::REQUIRED, 'Cible contenant les DRM en retour de CIEL'),
      new sfCommandArgument('drm', sfCommandArgument::REQUIRED, 'Identifiant DRM'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_REQUIRED, 'Interprofession', ''),
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'drm-cft';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $target = $arguments['target'];
    $drm = DRMClient::getInstance()->find($arguments['drm']);
    if (!$drm) {
        return;
    }
    $interpro = $options['interpro'];
    if ($interpro) {
    	$interpro = InterproClient::getInstance()->find($interpro);
    }
    $contextInstance = sfContext::createInstance($this->configuration);
    
    $list = simplexml_load_file($target, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $result = array();
    if ($list !== FALSE) {
        foreach ($list->children() as $item) {
            if (preg_match('/_([0-9]{8}).xml$/', $item, $r)) {
                $result[$r[1]] = $item;
            }
        }
    }
    if (count($result) > 0) {
        krsort($result);
        $xmlIn = simplexml_load_file(current($result), 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if ($xmlIn !== FALSE) {
            $export = new DRMExportCsvEdi($drm);
            if ($xml = $export->exportEDI('xml', $contextInstance)) {
                $xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
                $compare = new DRMCielCompare($xmlIn, $xmlOut);
                $rectif = $drm->findMaster();
                if ($rectif && $rectif->version == 'R01') {
                    $rectif->delete();
                }
                if (!$compare->hasDiff()) {
                   $drm->ciel->valide = 1;
                   $drm->save();
                   Email::getInstance()->cielValide($drm);
                } else {
                    if ($drm->isVersionnable()) {
					   $drm_rectificative = $drm->generateRectificative(true);
					   $drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
					   $drm_rectificative->add('ciel', $drm->ciel);
					   $drm_rectificative->ciel->xml = null;
					   $drm_rectificative->ciel->diff = $xmlIn->asXML();
					   $drm_rectificative->save();
                	   Email::getInstance()->cielRectificative($drm, $compare->getLitteralDiff(), $interpro);
                    }
                }
            }
        }
    }
  }
}
