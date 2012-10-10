<?php

/**
 * stats actions.
 *
 * @package    declarvin
 * @subpackage stats
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statsActions extends sfActions
{

  public function executeDRMVolumes(sfWebRequest $request) {

    $this->forward404Unless($campagne = $request->getParameter('campagne', null));
    $mois = $request->getParameter('mois', null);
    
    if ($campagne && $mois) {
      $rows = DRMStatsVolumesView::getInstance()->getByCampagneAndMois($campagne, $mois, DRMStatsVolumesView::KEY_APPELLATION + 1);
    } elseif($campagne) {
      $rows = DRMStatsVolumesView::getInstance()->getByCampagne($campagne, DRMStatsVolumesView::KEY_APPELLATION + 1);
    }
    
    array_unshift($rows, array('campagne', 'mois', 'certification', 'genre', 'denomination' , 'volume'));

    foreach($rows as $row)  {
        $this->getResponse()->setContent($this->getResponse()->getContent().implode(";", $row)."\n");
    }

    $filename = sprintf("drm_volumes_%s.csv", str_replace('-', '', $campagne).$mois);

    $this->response->setContentType('application/csv');
    $this->response->setHttpHeader('Content-disposition', 'filename='.$filename, true);
    $this->response->setHttpHeader('Pragma', 'o-cache', true);
    $this->response->setHttpHeader('Expires', '0', true);

    return sfView::NONE;
  }
}
