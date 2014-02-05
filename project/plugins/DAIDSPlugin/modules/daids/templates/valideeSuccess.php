<?php include_component('global', 'navTop', array('active' => 'daids')); ?>
<section id="contenu">
	<h1>Un problème est survenu</h1>
	<p>Vous avez déjà validé votre DAIDS.</p>
	<p>Si vous souhaitez effectuer des corrections, veuillez saisir une DAIDS rectificative.</p>
	<div id="btn_etape_dr">
    	<a href="<?php echo url_for('daids_mon_espace', $etablissement) ?>" class="btn_suiv">
			<span>Retour à mon espace</span>
		</a>
	</div>
</section>