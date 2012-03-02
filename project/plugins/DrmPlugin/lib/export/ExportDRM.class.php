<?php
class ExportDRM 
{
	protected $drm;
	
	public function __construct($drm)
	{
		$this->setDrm($drm);
	}
	
	public function getDrm()
	{
		return $this->drm;
	}
	public function setDrm($drm)
	{
		$this->drm = $drm;
	}

    protected static function getPartial($partial, $vars = null) 
    {
        return sfContext::getInstance()->getController()->getAction('drm_export', 'main')->getPartial('drm_export/' . $partial, $vars);
    }
	
}