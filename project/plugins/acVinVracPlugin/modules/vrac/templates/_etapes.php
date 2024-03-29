
<?php if ($configurationVrac->isContratPluriannuelActif()): ?>
<h1 style="margin:0px;">Nouveau contrat <?php if ($pluriannuel): ?>pluriannuel cadre<?php elseif($referenceContratPluriannuel): ?>adossé au contrat pluriannuel cadre n°<a target="_blank" href="<?php echo url_for('vrac_visualisation', array('contrat' => $referenceContratPluriannuel)) ?>"><?php echo $referenceContratPluriannuel ?></a><?php else: ?>ponctuel<?php endif; ?></h1>
<?php endif; ?>
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
<?php if ($actif != 'validation'): ?>
<script type="text/javascript">
	$("#rail_etapes a").click(function() {
		var link = $(this);
		$.ajax({type:"POST", data: $("form").serialize(), url: $("form").attr('action'),
			success: function(data){
				window.location.href = link.attr('href');
			},
			error: function(){
				$('#ajax_form_error_notification').show();
			}
		});
		return false;
	});
</script>
<?php endif; ?>