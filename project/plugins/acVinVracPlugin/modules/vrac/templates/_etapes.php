<div id="contrats_etapes">
    <ol id="rail_etapes">
            <?php 
                    $nbEtapes = count($etapes);
                    $counter = 0;
                    $first = true;
                    $isPrev = true;
                    foreach ($etapes as $etape => $etapeLibelle) {
                            $counter++;
                            if ($actif == $etape) {
                            	$isPrev = false;
                            }
                            include_partial('etapeItem',array('vrac' => $vrac, 'etablissement' => $etablissement, 'actif' => $actif, 'etape' => $etape, 'label' => $etapeLibelle, 'isActive' => ($actif == $etape), 'isLink' => !$configurationVracEtapes->hasSupForNav($etape, $vracEtape), 'last' => ($nbEtapes == $counter), 'first' => $first, 'isPrev' => $isPrev));
                            if ($first) {
                                $first = false;
                            }
                    }	
            ?>  
    </ol>
</div>