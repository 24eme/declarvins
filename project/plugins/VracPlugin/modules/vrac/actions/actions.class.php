<?php
class vracActions extends acVinVracActions
{
	public function getForm($interproId, $etape, $configurationVrac, $vrac)
	{
		return VracFormDeclarvinFactory::create($interproId, $etape, $configurationVrac, $vrac);
	}

}