<div id="statut_declaration">
	<nav id="declaration_etapes">
		<ol>
			<li class="premier <?php echo ($numero > 1) ? 'passe' : '' ?> <?php echo ($numero == 1) ? 'actif' : '' ?>"><a href="<?php echo url_for('@drm_informations') ?>">1. Informations</a></li>
			<li class="<?php echo ($numero > 2) ? 'passe' : '' ?> <?php echo ($numero == 2) ? 'actif' : '' ?>">
                            <a href="<?php echo url_for('drm_mouvements_generaux') ?>">2. Ajouts / Liquidations</a>
                        </li>
                        <?php foreach($certifications as $key => $label): ?>
                        <li class="<?php echo ($numero > $key) ? 'passe' : '' ?> <?php echo ($numero == $key) ? 'actif' : '' ?>">
                            <a href="<?php echo url_for('drm_recap', $config_certifications->get($label)) ?>"><?php echo $key ?>. <?php echo $label ?></a>
                        </li>
                        <?php endforeach; ?>
			<li <?php echo ($numero > $numero_validation) ? 'passe' : '' ?> <?php echo ($numero == $numero_validation) ? 'actif' : '' ?>>
                            <span><?php echo $numero_validation ?>. Validation</span>
                        </li>
		</ol>
	</nav>
	
	<div id="etat_avancement">
		<p>Vous avez saisi <strong><?php echo $pourcentage ?><span>%</span></strong></p>
		<div id="barre_avancement">
			<div style="width: <?php echo $pourcentage ?>%"></div>
		</div>
	</div>
</div>