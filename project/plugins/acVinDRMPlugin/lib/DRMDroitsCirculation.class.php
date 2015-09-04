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
	const KEY_CUMUL = 'cumul';
	const KEY_REPORT = 'report';
	const KEY_TOTAL = 'total';
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
				$this->droits[$c][$certif][self::KEY_CUMUL] = ($c == 'L387')? 0 : null;
				$this->droits[$c][$certif][self::KEY_REPORT] = ($c == 'L387')? 0 : null;
				$this->droits[$c][$certif][self::KEY_TOTAL] = ($c == 'L387')? 0 : null;
			}
		}
	}
	
	public function calculDroits() 
    {
    	$correspondances = array();
        foreach ($this->drm->getDetails() as $detail) {
        	$certification = $detail->getCertification()->getKey();
        	if ($detail->douane) {
        		if (!isset($correspondances[$detail->douane->code])) {
        			$correspondances[$detail->douane->code] = array();
        		}
        		$tab = $correspondances[$detail->douane->code];
        		$tab[] = $certification;
        		$correspondances[$detail->douane->code] = array_unique($tab);
        	}
        }
        foreach ($this->drm->droits->douane as $droit) {
        	if ($code = $this->getCorrespondanceCode($droit->code)) {
        		foreach ($correspondances[$droit->code] as $certif) {
	        		$this->droits[$code][$certif][self::KEY_VOLUME_REINTEGRATION] = $droit->volume_reintegre;
	        		$this->droits[$code][$certif][self::KEY_VOLUME_TAXABLE] = $droit->volume_taxe;
        			$this->droits[$code][$certif][self::KEY_REPORT] = $droit->report;
        			$this->droits[$code][$certif][self::KEY_CUMUL] = $droit->cumul;
	        		$this->droits[$code][$certif][self::KEY_TAUX] = $droit->taux;
        		}
	        	$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_VOLUME_REINTEGRATION] += $droit->volume_reintegre;
	        	$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_VOLUME_TAXABLE] += $droit->volume_taxe;
	        	$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_TAUX] = $droit->taux;
        		$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_REPORT] += $droit->report;
        		$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_CUMUL] += $droit->cumul;
        		$this->droits[$code][self::CERTIFICATION_TOTAL][self::KEY_TOTAL] += $droit->total;
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
    	$total = $this->droits[$code][$certification][self::KEY_VOLUME_TAXABLE] * $this->droits[$code][$certification][self::KEY_TAUX];
    	$result = null;
    	if ($total !== null) {
    		if ($total < 0) {
    			$total = 0;
    		}
    		return round($total);
    	}
    	return $result;
    }
    
    public function getCumulable($code, $certification)
    {
    	$cumul = $this->droits[$code][$certification][self::KEY_CUMUL];
    	$result = null;
    	if ($cumul !== null) {
    		if ($cumul < 0) {
    			$cumul = 0;
    		}
    		return round($cumul);
    	}
    	return $result;
    }
    
    public function getReportable($code, $certification)
    {
    	$report = $this->droits[$code][$certification][self::KEY_REPORT];
    	$result = null;
    	if ($report !== null) {
    		if ($report < 0) {
    			$report = 0;
    		}
    		return round($report);
    	}
    	return $result;
    }
    
    public function getTotalCumulable()
    {
    	$total = 0;
    	foreach (self::$codes as $c) {
    		$total += (($val = $this->getCumulable($c, self::CERTIFICATION_TOTAL)) !== null)? $val : 0;
    	}
    	return round($total);
    }
    
    public function getTotalReportable()
    {
    	$total = 0;
    	foreach (self::$codes as $c) {
    		$total += (($val = $this->getReportable($c, self::CERTIFICATION_TOTAL)) !== null)? $val : 0;
    	}
    	return round($total);
    }
    
    public function getTotalPayable()
    {
    	$total = 0;
    	foreach (self::$codes as $c) {
    		$total += (($val = $this->getPayable($c, self::CERTIFICATION_TOTAL)) !== null)? $val : 0;
    	}
    	return round($total);
    }
	
  
}
