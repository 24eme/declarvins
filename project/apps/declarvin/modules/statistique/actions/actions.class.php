<?php

/**
 * statistique actions.
 *
 * @package    declarvin
 * @subpackage statistique
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statistiqueActions extends sfActions {


	public function executeDematDrm(sfWebRequest $request) {
		$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
		$this->periodeMonth = $request->getGetParameter('periode_month', date('m'));
		$this->periodeYear = $request->getGetParameter('periode_year', date('Y'));
		$this->lot = $request->getGetParameter('lot', 2);
		$this->periode = sprintf('%04d-%02d', $this->periodeYear, $this->periodeMonth);
        $this->stats = $this->getStats($this->interpro, $this->periode, $this->lot);
	}

    public function executeBilanDrm(sfWebRequest $request) {
        $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
    }

    private function getStats($interpro, $periode, $lot) {

        $cm = new CampagneManager('08-01');
        $csv = sfConfig::get('sf_web_dir').'/exports/BILANDRM/'.$interpro->identifiant.'/'.$cm->getCampagneByDate($periode.'-01').'/bilan.csv';

        $stats = array(
    		'PAPIER' => 0,
    		'DTI' => 0,
    		'DTI_PLUS' => 0,
    		'CIEL' => 0,
    		'TOTAL' => 0,
    		'DEMAT' => 0
    	);
        $correspondancesMoisCol = array(8 => 17,9 => 18,10 => 19,11 => 20,12 => 21,1 => 22,2 => 23,3 => 24,4 => 25,5 => 26,6 => 27,7 => 28);
        $mois = substr($periode, -2)*1;
        $col = $correspondancesMoisCol[$mois];
        $libelles = DRMClient::getAllLibellesStatusBilan();

        $content = file_get_contents($csv);
        if ($content) {
            $lines = explode(PHP_EOL, $content);
            foreach ($lines as $line) {
                if (!trim($line)) {
                    continue;
                }
                $lineArr = str_getcsv($line, ';');
        	    if ($lot == 2 && !(strtolower(trim($lineArr[15])) == 'producteur' || strtolower(trim($lineArr[16])) == 'vinificateur')) {
        	        continue;
        	    }
        	    if ($lot == 1 && !(strtolower(trim($lineArr[15])) == 'negociant' && strtolower(trim($lineArr[16])) != 'vinificateur')) {
        	        continue;
        	    }
                if (in_array(trim($lineArr[$col]), array($libelles[DRMClient::DRM_STATUS_BILAN_VALIDE_CIEL], $libelles[DRMClient::DRM_STATUS_BILAN_DIFF_CIEL], $libelles[DRMClient::DRM_STATUS_BILAN_ENVOYEE_CIEL]))) {
    				$stats['CIEL']++;
    				continue;
    			}
                if (in_array(trim($lineArr[$col]), array($libelles[DRMClient::DRM_STATUS_BILAN_VALIDE], $libelles[DRMClient::DRM_STATUS_BILAN_IGP_MANQUANT], $libelles[DRMClient::DRM_STATUS_BILAN_CONTRAT_MANQUANT], $libelles[DRMClient::DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT], $libelles[DRMClient::DRM_STATUS_BILAN_NON_VALIDE]))) {
                    $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($lineArr[0], $periode);
                    if (!$drm->mode_de_saisie) {
    					continue;
    				}
	    			if ($drm->mode_de_saisie == 'PAPIER') {
	    				$stats['PAPIER']++;
	    			} elseif ($drm->mode_de_saisie == 'DTI_PLUS') {
	    			    $stats['DTI_PLUS']++;
	    			} else {
	    				$stats['DTI']++;
	    			}
    			}
            }
        	$stats['TOTAL'] = $stats['PAPIER'] + $stats['DTI'] + $stats['CIEL'] + $stats['DTI_PLUS'];
        	$stats['DEMAT'] = ($stats['TOTAL'] > 0 && $stats['CIEL'] > 0)? round(($stats['CIEL'] / $stats['TOTAL']) * 100) : 0;
        }
    	return $stats;
    }

}
