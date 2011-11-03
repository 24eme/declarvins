<section id="contenu">
	<?php include_partial('global/navTop', array('active' => 'drm')) ?>
	<div id="creation_compte" style="width:100%;">
		<h1>Déclaration Récapitulative Mensuelle</h1>
		<p>DRM 2011 - MARS</p>
		<br />
		<?php include_partial('drm/etapes', array('active' => 'ajouts-liquidations', 'pourcentage' => '10')) ?>
		<?php include_partial('drm/onglets', array('active' => 'mouvements-generaux')) ?>
		<br />
		<div style="width: 70%; float:left;">
			<p>Au cours du mois écoulé, avez-vous connu des changements de structure particuliers ?</p>
			<br />
			<div style="margin: 10px;">
				<strong>&raquo; Pas de mouvement ni entrée, ni sortie de chai sur le mois (L3 à L11) en :</strong>
				<ul>
					<li><input type="checkbox" /><label>AOP</label></li>
					<li><input type="checkbox" /><label>IGP</label></li>
					<li><input type="checkbox" /><label>sans IG</label></li>
				</ul>
				<br />
				<strong>&raquo; Stock Epuisé (aucun vin en cave) en :</strong>
				<ul>
					<li><input type="checkbox" /><label>AOP</label></li>
					<li><input type="checkbox" /><label>IGP</label></li>
					<li><input type="checkbox" /><label>sans IG</label></li>
				</ul>
				<br />
				<input type="button" value="Valider &raquo;" onclick="javascript:document.location.href='<?php echo url_for('@drm_evolution') ?>'" />
			</div>
		</div>
		<?php include_partial('drm/astuces') ?>
		<div style="clear:both;">&nbsp;</div>
		<?php include_partial('global/boutons', array('nextUrl' => url_for('@drm_evolution'), 'prevUrl' => url_for('@drm_informations'))) ?>
	</div>
</section>