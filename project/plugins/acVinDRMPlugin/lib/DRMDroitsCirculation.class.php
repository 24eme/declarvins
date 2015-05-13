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
				$this->droits[$c][$certif][self::KEY_VOLUME_REINTEGRATION] = ($c == 'L387')? 0 : null;
				$this->droits[$c][$certif][self::KEY_VOLUME_TAXABLE] = ($c == 'L387')? 0 : null;
				$this->droits[$c][$certif][self::KEY_TAUX] = ($c == 'L387')? 0 : null;
			}
		}
	}
	
	public function calculDroits() 
    {
        foreach ($this->drm->getDetails() as $detail) {
	        $mergeSorties = array();
	    	$mergeEntrees = array();
	    	if ($detail->interpro == Interpro::INTERPRO_KEY.Interpro::INTER_RHONE_ID) {
	    		$mergeSorties = DRMDroits::getDroitSortiesInterRhone();
	    		$mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
	    	}
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
    
    public function getPayable($code, $certification)
    {
    	$taxable = $this->droits[$code][$certification][self::KEY_VOLUME_TAXABLE];
    	$reintegration = $this->droits[$code][$certification][self::KEY_VOLUME_REINTEGRATION];
    	$taux = $this->droits[$code][$certification][self::KEY_TAUX];
    	$payable = null;
    	if ($taxable !== null && $reintegration !== null && $taux !== null) {
    		$payable = ($taxable - $reintegration) * $taux;
    		if ($payable < 0) {
    			$payable = 0;
    		}
    		return round($payable, 4);
    	}
    	return $payable;
    }
    
    public function getTotalPayable()
    {
    	$total = 0;
    	foreach (self::$codes as $c) {
    		$total += (($val = $this->getPayable($c, self::CERTIFICATION_TOTAL)) !== null)? $val : 0;
    	}
    	return round($total, 4);
    }
	
  
}
