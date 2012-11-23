<?php
class ExportDAIDS 
{
	protected $daids;
	protected $pagers_volume;
    protected $pagers_code;

	const NB_COL = 8;
    const NB_COL_CODES = 3;
    const MAX_PER_COL = 31;

	public function __construct($daids)
	{
		$this->setDAIDS($daids);
		$this->create();
	}
	
	public function getDAIDS()
	{
		return $this->daids;
	}

	public function getPagersVolume()
	{
		
        return $this->pagers_volume;
	}

    public function getPagersCode()
    {
        
        return $this->pagers_code;
    }

	public function setDAIDS($daids)
	{

		$this->daids = $daids;
	}

    protected static function getPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('daids_export', 'main')->getPartial('daids_export/' . $partial, $vars);
    }

    protected function create()
    {
    	$this->pagers_volume = array();
    	foreach($this->daids->declaration->certifications as $certification) {
            $details_pour_volume = array();
            $codes = array();
            $details = $certification->getProduits();
    		foreach($details as $detail) {
    				$details_pour_volume[] = $detail;
                    $codes[$detail->getCode()] = $detail->getCepage();
    		}
            $this->pagers_volume[$certification->getKey()] = $this->makePager($details_pour_volume);
            $this->pagers_code[$certification->getKey()] = $this->makeColPager($codes, self::NB_COL_CODES, self::MAX_PER_COL);
    	}
    }

    protected function makePager($array, $nb_col = self::NB_COL, $fill_with_max = true) {
        $pager = new ArrayPager($nb_col, $fill_with_max);
        $pager->setArray($array);
        $pager->init();

        return $pager;
    }

    protected function makeColPager($array, $nb_col = self::NB_COL_CODES, $max_per_col = self::MAX_PER_COL, $fill_with_max = true) {
        $pager = new ArrayColPager($nb_col, $max_per_col, $fill_with_max);
        $pager->setArray($array);
        $pager->init();

        return $pager;
    }
}