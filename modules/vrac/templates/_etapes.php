<div id="contrats_etapes">
    <ol id="rail_etapes">
            <?php 
                    $nbEtapes = count($etapes);
                    $counter = 0;
                    $first = true;
                    foreach ($etapes as $etape => $etapeLibelle) {
                            $counter++;
                            include_partial('etapeItem',array('vrac' => $vrac, 'actif' => $actif, 'etape' => $etape, 'label' => $etapeLibelle, 'isActive' => ($actif == $etape), 'isLink' => !$configurationVracEtapes->hasSup($etape, $actif), 'last' => ($nbEtapes == $counter), 'first' => $first));
                            if ($first) {
                                $first = false;
                            }
                    }	
            ?>  
    </ol>
</div>