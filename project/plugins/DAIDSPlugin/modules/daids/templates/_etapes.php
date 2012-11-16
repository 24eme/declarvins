<div id="statut_declaration">
	<nav id="declaration_etapes">
		<ol>
                  <?php include_partial('daids/etapeItem', array('daids' => $daids,
                                                               'libelle' => 'Informations',
                                                               'numero' => 1,
                                                               'numero_courant' => $numero,
                                                               'numero_autorise' => $numero_autorise,
                                                               'url' => url_for('daids_informations', $daids),
                                                               'cssclass' => 'premier')); ?>

            <?php foreach($certifications as $key => $certification): ?>
                 <?php include_partial('daids/etapeItem', array('daids' => $daids,
                                                               'libelle' => $certification->getConfig()->getLibelle(),
                                                               'numero' => $key,
                                                               'numero_courant' => $numero,
                                                               'numero_autorise' => $numero_autorise,
                                                               'url' => url_for('daids_recap', $certification),
                                                               'cssclass' => null)); ?>
            <?php endforeach; ?>


            <?php include_partial('daids/etapeItem', array('daids' => $daids,
                                                               'libelle' => 'Validation',
                                                               'numero' => $numero_validation,
                                                               'numero_courant' => $numero,
                                                               'numero_autorise' => $numero_autorise,
                                                               'url' => '#', //url_for('daids_validation', $drm),
                                                               'cssclass' => 'dernier')); ?>
		</ol>
	</nav>	
	<div id="etat_avancement">
		<p>Vous avez saisi <strong><?php echo $pourcentage ?><span>%</span></strong></p>
		<div id="barre_avancement">
			<div style="width: <?php echo $pourcentage ?>%"></div>
		</div>
	</div>
</div>