<?php
class ExportDRM 
{
	protected $drm;
	protected $colonnes;
	const NB_COL = 5;
	
	public function __construct($drm)
	{
		$this->setDrm($drm);
		$this->create();
	}
	
	public function getDrm()
	{
		return $this->drm;
	}

	public function getColonnes()
	{
		return $this->colonnes;
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
    	$this->colonnes = array();

    	foreach($this->drm->produits as $certification) {
    		$this->colonnes[$certification->getKey()] = array();
    		$i = 0;
    		foreach($certification as $appellation) {
    			foreach($appellation as $produit) {
    				$col_i = floor($i / self::NB_COL);
    				$detail = $produit->getDetail();
    				$this->colonnes[$certification->getKey()][$col_i][$detail->getHash()] = $detail;
    				$i++;
    			}
    		}
    		if(count($this->colonnes[$certification->getKey()][$col_i]) < self::NB_COL) {
    			for($i = count($this->colonnes[$certification->getKey()][$col_i]); $i < self::NB_COL; $i++) {
    				$this->colonnes[$certification->getKey()][$col_i][] = null;
    			}
    		}
    	}
    }
}