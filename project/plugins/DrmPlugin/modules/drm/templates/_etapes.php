<div id="statut_declaration">
	<nav id="declaration_etapes">
		<ol>
			<li class="premier <?php echo ($numero > 1) ? 'passe' : '' ?> <?php echo ($numero == 1) ? 'actif' : '' ?>">
				<a href="<?php echo url_for('@drm_informations') ?>">
					<span>1. Informations</span>
				</a>
			</li>
			<li class="<?php echo ($numero > 2) ? 'passe' : '' ?> <?php echo ($numero == 2) ? 'actif' : '' ?>">
            	<a href="<?php echo url_for('drm_mouvements_generaux') ?>">
            		<span>2. Ajouts / Liquidations</span>
            	</a>
            </li>
            <?php foreach($certifications as $key => $certification): ?>
            <li class="<?php echo ($numero > $key) ? 'passe' : '' ?> <?php echo ($numero == $key) ? 'actif' : '' ?>">
            	<a href="<?php echo url_for('drm_recap', $config_certifications->get($certification)) ?>">
            		<span><?php echo $key ?>. <?php echo $certification ?></span>
            	</a>
	        </li>
            <?php endforeach; ?>
            <li class="<?php echo ($numero > $numero_vrac) ? 'passe' : '' ?> <?php echo ($numero == $numero_vrac) ? 'actif' : '' ?>">
            	<a href="<?php echo url_for('drm_vrac') ?>">
            		<span><?php echo $numero_vrac ?>. Vrac</span>
            	</a>
            </li>
			<li class="dernier <?php echo ($numero > $numero_validation) ? 'passe' : '' ?> <?php echo ($numero == $numero_validation) ? 'actif' : '' ?>">
            	<span>
            		<span><?php echo $numero_validation ?>. Validation</span>
            	</span>
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