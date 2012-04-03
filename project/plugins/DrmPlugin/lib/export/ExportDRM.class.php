<?php
class ExportDRM 
{
	protected $drm;
	protected $pagers_volume;
    protected $pagers_vrac;
    protected $pagers_code;
    protected $pager_droits_douane;

	const NB_COL = 8;
    const NB_COL_CODES = 3;

	public function __construct($drm)
	{
		$this->setDrm($drm);
		$this->create();
	}
	
	public function getDrm()
	{
		return $this->drm;
	}

	public function getPagersVolume()
	{
		
        return $this->pagers_volume;
	}

    public function getPagersVrac()
    {
        
        return $this->pagers_vrac;
    }

    public function getPagerDroitsDouane()
    {
        
        return $this->pager_droits_douane;
    }

    public function getPagersCode()
    {
        
        return $this->pagers_code;
    }

	public function setDrm($drm)
	{

		$this->drm = $drm;
	}

    protected static function getPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('drm_export', 'main')->getPartial('drm_export/' . $partial, $vars);
    }

    protected function create()
    {
    	$this->pagers_volume = array();
        $this->pagers_vrac = array();
        

    	foreach($this->drm->produits as $certification) {
            $details_pour_volume = array();
            $details_pour_vrac = array();
            $codes = array();
    		foreach($certification as $appellation) {
    			foreach($appellation as $produit) {
    				$detail = $produit->getDetail();
    				$details_pour_volume[] = $detail;
                    foreach($detail->vrac as $vrac) {
                        $details_pour_vrac[] = $vrac;
                    }
                    $codes[$detail->getMillesime()->getHash().uniqid()] = $detail->getMillesime();
    			}
    		}
            $this->pagers_volume[$certification->getKey()] = $this->makePager($details_pour_volume);
            $this->pagers_vrac[$certification->getKey()] = $this->makePager($details_pour_vrac);
            $this->pagers_code[$certification->getKey()] = $this->makePager($codes, self::NB_COL_CODES);
    	}

        $this->pager_droits_douane = $this->makePager($this->drm->droits->douane->getDroitsWithVirtual());
    }

    protected function makePager($array, $nb_col = self::NB_COL, $fill_with_max = true) {
        $pager = new ArrayPager($nb_col, $fill_with_max);
        $pager->setArray($array);
        $pager->init();

        return $pager;
    }
}