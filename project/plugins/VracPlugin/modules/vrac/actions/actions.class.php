<?php
class vracActions extends acVinVracActions
{
	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $vrac)
	{
		return VracFormDeclarvinFactory::create($interproId, $etape, $configurationVrac, $etablissement, $vrac);
	}

}