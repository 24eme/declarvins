<section id="contenu">
	<?php include_partial('global/navTop', array('active' => 'drm')) ?>
	<div id="creation_compte" style="width:100%;">
		<h1>Déclaration Récapitulative Mensuelle</h1>
		<p>DRM 2011 - MARS</p>
		<br />
		<?php include_partial('drm/etapes', array('active' => 'ajouts-liquidations', 'pourcentage' => '20')) ?>
		<?php include_partial('drm/onglets', array('active' => 'evolution')) ?>
		<br />
		<div style="width: 70%; float:left;">
			<p>Par rapport au mois précédent, avez-vous abandonné une appellation ou décidé d'en produire une nouvelle ?</p>
			<br />
			<div style="margin: 10px;">
				<strong>&raquo; Lors de votre précédente DRM, certains stocks étaient nuls, souhaitez-vous ne plus déclarer de stocks sur ces appelations :</strong>
				<ul>
					<li style="display: inline-block; width:100px;">Stock NUL en : </li><li style="display: inline-block; width: 200px">Vacqueyras Rouge</li><li style="display: inline-block;"><input type="checkbox" /><label>Je conserve cette appelation</label></li>
					<li />
					<li style="display: inline-block; width:100px;">Stock NUL en : </li><li style="display: inline-block; width: 200px;">AOC Côte du Ventoux Rouge</li><li style="display: inline-block;"><input type="checkbox" /><label>Je conserve cette appelation</label></li>
				</ul>
				<br />
				<strong>&raquo; Vous souhaitez ajouter une nouvelle appelation à votre stock :</strong>
                                <br /><br />
                                <div style="float:left;">
				<?php foreach($configuration->declaration->labels as $label): ?>
                                <div style="float:left; width: 190px;">
                                <strong><?php echo $label->libelle ?></strong>
				<ul>
                                    <?php foreach($label->appellations as $appellation): ?>
					<li><input id="appellation_<?php echo $appellation->getKey() ?>" type="checkbox" /><label for="appellation_<?php echo $appellation->getKey() ?>" ><?php echo $appellation->libelle ?></label></li>
                                    <?php endforeach; ?>
				</ul>
                                </div>
                                <?php endforeach; ?>
                                </div>
				<br />
				<input type="button" value="Valider &raquo;" />
			</div>
		</div>
		<?php include_partial('drm/astuces') ?>
		<div style="clear:both;">&nbsp;</div>
		<?php include_partial('global/boutons', array('nextUrl' => '#', 'prevUrl' => url_for('@drm_mouvements_generaux'))) ?>
	</div>
</section>