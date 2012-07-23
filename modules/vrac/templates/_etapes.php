<ol id="rail_etapes">
	<?php 
		foreach ($etapes as $etape => $etapeLibelle) {
			if (!$vrac->has_transaction && $etape == 'transaction')
				continue;
			include_partial('etapeItem',array('vrac' => $vrac, 'actif' => $actif, 'etape' => $etape, 'label' => $etapeLibelle, 'isActive' => ($actif == $etape), 'isLink' => !$configurationVracEtapes->hasSup($etape, $actif)));
		}	
	?>  
</ol>