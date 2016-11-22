<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>

<section id="contenu" class="vracs">
		<h1>Adhésion CIEL</h1>
		<p style="text-align: center;">
		L'adhésion au service CIEL, vous permez de dématérialiser vos DRM avec les douanes...
		</p>
		<div class="ligne_form_btn">
			<a class="valider_etape" onclick="return confirm('Confirmez-vous l\'adhésion au service CIEL ?')" href="<?php echo url_for('tiers_acceptation_ciel', $etablissement)?>"><span>J'adhère au service CIEL</span></a>
			<a class="annuler_saisie" href="<?php echo url_for('drm_mon_espace', $etablissement)?>"><span>Plus tard</span></a>
		</div>
</section>