<?php

class DRMDroitsCirculation
{
	protected $drm;
	protected $droits;
	const CERTIFICATION_AOP = 'AOP';
	const CERTIFICATION_IGP = 'IGP';
	const CERTIFICATION_VINSSANSIG = 'VINSSANSIG';
	const CERTIFICATION_TOTAL = 'TOTAL';
	const KEY_VOLUME_REINTEGRATION = 'reintegration';
	const KEY_VOLUME_TAXABLE = 'taxable';
	const KEY_TAUX = 'taux';
	protected static $codes = array(
		'L387',
		'L385',
		'L423',
		'L425',
		'L440'
	);
	protected static $certifications = array(
		self::CERTIFICATION_AOP, 
		self::CERTIFICATION_IGP, 
		self::CERTIFICATION_VINSSANSIG,
		self::CERTIFICATION_TOTAL
	);
	
	public function __construct(DRM $drm)
	{
		$this->drm = $drm;
		$this->initDroits();
		$this->calculDroits();
	}
	
	public function initDroits()
	{
		$this->droits = array();
		foreach (self::$codes as $c) {
			$this->droits[$c] = array();
			foreach (self::$certifications as $certif) {
				$this->droits[$c][$certif] = array();
				$this->droits[$c][$certif][self::KEY_VOLUME_REINTEGRATION] = 0;
				$this->droits[$c][$certif][self::KEY_VOLUME_TAXABLE] = 0;
				$this->droits[$c][$certif][self::KEY_TAUX] = 0;
			}
		}
	}
	
	public function calculDroits() 
    {
    	$mergeSorties = array();
    	$mergeEntrees = array();
    	if ($this->drm->getInterpro()->getKey() == Interpro::INTERPRO_KEY.Interpro::INTER_RHONE_ID) {
    		$mergeSorties = DRMDroits::getDroitSortiesInterRhone();
    		$mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
    	}
        foreach ($this->drm->getDetails() as $detail) {
        	$certification = $detail->getCertification()->getKey();
        	$droit = $detail->getDroit(ConfigurationProduit::NOEUD_DROIT_DOUANE);
        	$taux = ($droit)? $droit->taux : 0;
        	$taux = ($taux)? $taux : 0;
        	if ($detail->douane) {
        		if ($code = $this->getCorrespondanceCode($detail->douane->code)) {
        			$taxable = $detail->sommeLignes(DRMDroits::getDouaneDroitSorties());
        			$reintegration = $detail->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees));
        			$this->droits[$code][$certification][self::KEY_VOLUME_REINTEGRATION] += $reintegration;
        			$this->droits[$code][$certification][self::KEY_VOLUME_TAXABLE] += $taxable;
        			$this->droits[$code][$certification][self::KEY_TAUX] = $taux;
        			$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_VOLUME_REINTEGRATION] += $reintegration;
        			$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_VOLUME_TAXABLE] += $taxable;
        			$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_TAUX] = $taux;
        		}
        	}
        }
    }
    
    public function getCorrespondanceCode($code)
    {
    	foreach (self::$codes as $c) {
    		if (preg_match('/'.$c.'/i', $code)) {
    			return $c;
    		}
    	}
    	return null;
    }
    
    public function getDroits()
    {
    	return $this->droits;
    }
	
  
}
