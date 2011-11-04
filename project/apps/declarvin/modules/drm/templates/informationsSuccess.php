<section id="contenu">
	<?php include_partial('global/navTop', array('active' => 'drm')) ?>
	<div id="creation_compte" style="width:100%; float: left;">
		<h1>Déclaration Récapitulative Mensuelle</h1>
		<p>DRM 2011 - MARS</p>
		<br />
		<?php include_partial('drm/etapes', array('active' => 'informations', 'pourcentage' => '5')) ?>
		<p>Veuillez tout d'abord confirmer les informations ci-dessous :</p>
		<ul style="width: 780px; float:left; margin: 50px;">
			<li style="width: 50%; float:left; margin: 5px 0;"><b>CVI :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->cvi ?>&nbsp;</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>N° SIRET :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->siret ?>&nbsp;</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>N° entrepositaire agréé :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->no_tva_intracommunautaire ?>&nbsp;</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Nom / Raison Sociale / Adresse :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->nom ?>,<br /><?php echo $tiers->siege->adresse ?><br /><?php echo $tiers->siege->code_postal ?> <?php echo $tiers->siege->commune ?>&nbsp;</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Lieu ou est tenu la comptabilité matière :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><?php if (!$tiers->comptabilite->adresse): ?>IDEM<?php else: ?><?php echo $tiers->comptabilite->adresse ?><br /><?php echo $tiers->comptabilite->code_postal ?> <?php echo $tiers->comptabilite->commune ?><?php endif; ?>&nbsp;</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Service des douanes :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->service_douane ?>&nbsp;</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Adresse et n° du Chai :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;">?</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Caution RR :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;">?</li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Je confirme l'exactitude de ces informations :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><input type="checkbox" /></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><b>Je souhaite modifier mes informations de structure :</b></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><input type="checkbox" /></li>
			<li style="width: 50%; float:left; margin: 5px 0;"><input type="button" value="Valider &raquo;" onclick="javascript:document.location.href='<?php echo url_for('@drm_mouvements_generaux') ?>'" /></li>
		</ul>
	</div>
	<div style="clear:both;">&nbsp;</div>
</section>