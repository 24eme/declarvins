<?php

class StatistiquesBilan {

    const KEY_DRM_LIST = '_drms';

    protected $interpro;
    protected $campagne;
    protected $periodes;
    protected $periodesNMoins1;
    protected $bilans;

    public function __construct($interpro, $campagne) {
        $this->interpro = InterproClient::getInstance()->find($interpro);
        $this->campagne = $campagne;
        $this->buildPeriodes();
        $this->buildPeriodesNMoins1();
        $this->buildBilans();
    }

    private function buildBilans() {
        $bilansByRegionAll = StatistiquesBilanView::getInstance()->findDrmByRegion($this->interpro->getZone())->rows;
        $this->bilans = array();
        foreach ($bilansByRegionAll as $bilanByRegion) {
            $bilan = new stdClass();
            $bilan->id = $bilanByRegion->id;
            $bilan->region = $this->interpro->getZone();
            $bilanDatas = $bilanByRegion->value[0];
            $bilan->identifiant = $bilanDatas->identifiant;
            $bilan->etablissement = $bilanDatas->etablissement;
            $bilan->periodes = array();
            $first = null;
            $last = null;
            $lastSaisie = null;
            $lastStatut = null;
            foreach ($bilanDatas->periodes as $periodesKey => $periodesValues) {
	    		if (!$first && $periodesValues->id_drm) {
	    			$first = $periodesKey;
	    		}
                if (in_array($periodesKey, $this->periodes)) {
                    $bilan->periodes[$periodesKey] = $periodesValues;
                }
	    		if ($periodesValues->id_drm) {
	    			$lastSaisie = $periodesKey;
                	$last = $periodesValues->total_fin_de_mois;
                	$lastStatut = $periodesValues->statut;
	    		}
            }
            $bilan->first_periode = $first;
            $bilan->last_stock = $last;
            $bilan->last_saisie = $lastSaisie;
            $bilan->last_statut = $lastStatut   ;
            
            $bilan->periodesNMoins1 = array();
            foreach ($bilanDatas->periodes as $periodesKey => $periodesValues) {
                if (in_array($periodesKey, $this->periodesNMoins1)) {
                    $bilan->periodesNMoins1[$periodesKey] = $periodesValues;
                }
            }
            
            $this->bilans[] = $bilan;
        }
    }

    private function buildPeriodes() {
        $this->periodes = array();
        $stopPeriode = date('Y-m');
        $months = array('08', '09', '10', '11', '12', '01', '02', '03', '04', '05', '06', '07');
        $years = explode('-', $this->campagne);
        foreach ($months as $month) {
            if ($month < 8) {
                $periode = $years[1] . '-' . $month;
            } else {
                $periode = $years[0] . '-' . $month;
            }
            if ($periode > $stopPeriode) {
                break;
            }
            $this->periodes[] = $periode;
        }
        return $this->periodes;
    }
    
    private function buildPeriodesNMoins1() {
        $this->periodesNMoins1 = array();
        $months = array('08', '09', '10', '11', '12', '01', '02', '03', '04', '05', '06', '07');
        $years = explode('-', $this->campagne);
        foreach ($months as $month) {
            if ($month < 8) {
                $periode = ((int) $years[1] - 1) . '-' . $month;
            } else {
                $periode = ((int) $years[0] - 1). '-' . $month;
            }
            $this->periodesNMoins1[] = $periode;
        }
        return $this->periodesNMoins1;
    }
    

    public function getPeriodes() {
        return $this->periodes;
    }

    public function getBilans() {
        return $this->bilans;
    }

    public function getEtablissementFieldCsv($bilanOperateur) {
        return $bilanOperateur->identifiant . ";"
                . $bilanOperateur->etablissement->raison_sociale . ";"
                . $bilanOperateur->etablissement->nom . ";"
                . $bilanOperateur->etablissement->siret . ";"
                . $bilanOperateur->etablissement->cvi . ";"
                . $bilanOperateur->etablissement->no_accises . ";"
                . $bilanOperateur->etablissement->siege->adresse . ";"
                . $bilanOperateur->etablissement->siege->code_postal . ";"
                . $bilanOperateur->etablissement->siege->commune . ";"
                . $bilanOperateur->etablissement->siege->pays . ";"
                . $bilanOperateur->etablissement->email . ";"
                . $bilanOperateur->etablissement->telephone . ";"
                . $bilanOperateur->etablissement->fax . ";"
                . $bilanOperateur->etablissement->service_douane . ";"
                . $bilanOperateur->etablissement->statut . ";"
                . $bilanOperateur->etablissement->famille . ";"
                . $bilanOperateur->etablissement->sous_famille . ";";
    }
    
    public function getStatutsDrmsCsv($bilanOperateur) {
        $statutsDrmsCsv = "";
        $libelles = DRMClient::getAllLibellesStatusBilan();
        $firstSaisie = $bilanOperateur->first_periode;
        $lastStock = $bilanOperateur->last_stock;
        $lastSaisie = $bilanOperateur->last_saisie;
        $lastStatut = $bilanOperateur->last_statut;
        foreach ($this->buildPeriodes() as $periode) {
        	if ($firstSaisie && $periode >= $firstSaisie) {
        		if ($lastSaisie && $periode > $lastSaisie && !$lastStock && $lastStatut != DRMClient::DRM_STATUS_BILAN_NON_VALIDE) {
        			$statutsDrmsCsv .= $libelles[DRMClient::DRM_STATUS_BILAN_STOCK_EPUISE].";";
        		} else {
	        	if (!isset($bilanOperateur->periodes[$periode]) || is_null($bilanOperateur->periodes[$periode])) {
	        		$statutsDrmsCsv .= $libelles[DRMClient::DRM_STATUS_BILAN_A_SAISIR].";";
	        	} else {
		        	if (isset($bilanOperateur->periodes[$periode]->mode_de_saisie) && $bilanOperateur->periodes[$periode]->mode_de_saisie) {
		        		$statutsDrmsCsv .= $bilanOperateur->periodes[$periode]->mode_de_saisie." ";
		        	}
		            $statutsDrmsCsv .= $libelles[$bilanOperateur->periodes[$periode]->statut].";";
	        	}
        		}
        	} else {
        		$statutsDrmsCsv .= ";";
        	}
        }
        return $statutsDrmsCsv;
    }
            
    public function hasEtablissementStatutManquantForPeriode($bilanOperateur, $periode) {
    	if (!isset($bilanOperateur->periodes[$periode])) {
    		return false;
    	}
        return ($bilanOperateur->periodes[$periode]->statut == DRMClient::DRM_STATUS_BILAN_A_SAISIR);
    } 
    
    public function getStats($periode, $lot = 2) {
    	$stats = array(
    		'PAPIER' => 0,
    		'DTI' => 0,
    		'CIEL' => 0,
    		'TOTAL' => 0,
    		'DEMAT' => 0
    	);
    	foreach ($this->getBilans() as $bilanOperateur) {
    	    if ($lot == 2 && !($bilanOperateur->etablissement->famille == 'producteur' || $bilanOperateur->etablissement->sous_famille == 'vinificateur')) {
    	        continue;
    	    }
    	    if ($lot == 1 && !($bilanOperateur->etablissement->famille == 'negociant' && $bilanOperateur->etablissement->sous_famille != 'vinificateur')) {
    	        continue;
    	    }
    		if ($bilanOperateur->first_periode && $periode >= $bilanOperateur->first_periode) {
    			if ($bilanOperateur->last_saisie && $periode > $bilanOperateur->last_saisie && !$bilanOperateur->last_stock) {
    				continue;
    			}
    			if (!isset($bilanOperateur->periodes[$periode]) || is_null($bilanOperateur->periodes[$periode])) {
    				continue;
    			}
    			
    			if (in_array($bilanOperateur->periodes[$periode]->statut, array(DRMClient::DRM_STATUS_BILAN_VALIDE_CIEL, DRMClient::DRM_STATUS_BILAN_DIFF_CIEL, DRMClient::DRM_STATUS_BILAN_ENVOYEE_CIEL))) {
    				$stats['CIEL'] = $stats['CIEL']+1;
    				continue;
    			}
    			
    			if (in_array($bilanOperateur->periodes[$periode]->statut, array(DRMClient::DRM_STATUS_BILAN_VALIDE, DRMClient::DRM_STATUS_BILAN_IGP_MANQUANT, DRMClient::DRM_STATUS_BILAN_CONTRAT_MANQUANT, DRMClient::DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT, DRMClient::DRM_STATUS_BILAN_NON_VALIDE))) {
    				if (!isset($bilanOperateur->periodes[$periode]->mode_de_saisie) || !$bilanOperateur->periodes[$periode]->mode_de_saisie) {
    					continue;
    				}
	    			if (trim($bilanOperateur->periodes[$periode]->mode_de_saisie) == 'PAPIER') {
	    				$stats['PAPIER'] = $stats['PAPIER']+1;
	    			} else {
	    				$stats['DTI'] = $stats['DTI']+1;
	    			}
    			}
    		}
    	}
    	$stats['TOTAL'] = $stats['PAPIER'] + $stats['DTI'] + $stats['CIEL'];
    	$stats['DEMAT'] = ($stats['TOTAL'] > 0 && $stats['CIEL'] > 0)? round(($stats['CIEL'] / $stats['TOTAL']) * 100) : 0;
    	return $stats;
    }
    
}
