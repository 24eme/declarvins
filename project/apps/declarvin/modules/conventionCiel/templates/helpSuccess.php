<?php use_helper("Date"); ?>
<?php include_component('global', 'navTop', array('active' => '')); ?>
<style>
#creation_compte .ligne_form label {
	width: 90px !important;
}
#creation_compte .ligne_form input[type="text"], #creation_compte .ligne_form input[type="password"] {
	width: 300px !important;
}
#creation_compte .ligne_form textarea {
	background: #fff none repeat scroll 0 0;
    border: 1px solid #d3d2cd;
    border-radius: 2px;
    font-size: 12px;
    position: relative;
    vertical-align: middle;
    width: 305px !important;
    height: 100px;
}
.intro {
	font-weight: bold !important;
}
</style>
<section id="contenu">
    <div id="creation_compte">
        <h1>Assistance CIEL</h1>
        <?php if($sf_user->hasFlash('assistance')) : ?>
        <div style="background: none repeat scroll 0 0 #ECFEEA;border: 1px solid #359B02;color: #1E5204;font-weight: bold;margin: 0 0 10px 0;padding: 5px 10px;">
			<ul><li><?php echo $sf_user->getFlash('assistance'); ?></li></ul>
        </div>
        <?php else: ?>
        <form method="post" action="<?php echo url_for('ciel_help', $etablissement) ?>">
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->renderGlobalErrors(); ?>
	        <p class="intro">Veuillez saisir les informations de la personne à recontacter :</p>
	        <div class="col">
				<div class="ligne_form">
					<?php echo $form['nom']->renderError() ?>
					<?php echo $form['nom']->renderLabel() ?>
					<?php echo $form['nom']->render() ?>
				</div>
				<div class="ligne_form">
					<?php echo $form['prenom']->renderError() ?>
					<?php echo $form['prenom']->renderLabel() ?>
					<?php echo $form['prenom']->render() ?>
				</div>
				<div class="ligne_form">
					<?php echo $form['telephone']->renderError() ?>
					<?php echo $form['telephone']->renderLabel() ?>
					<?php echo $form['telephone']->render() ?>
					<br /><span style="font-style: italic; font-size: 11px; padding-left: 93px;">Format : 10 chiffres (ex: 0490272400)</span>
				</div>
				<div class="ligne_form">
					<?php echo $form['email']->renderError() ?>
					<?php echo $form['email']->renderLabel() ?>
					<?php echo $form['email']->render() ?>
				</div>
				<p class="intro">Veuillez saisir les informations de l'incident rencontré :</p>
				<div class="ligne_form">
					<?php echo $form['sujet']->renderError() ?>
					<?php echo $form['sujet']->renderLabel() ?>
					<?php echo $form['sujet']->render(array(), array('cols' => 20, 'rows' => 8)) ?>
				</div>
				<div class="ligne_form">
					<?php echo $form['message']->renderError() ?>
					<?php echo $form['message']->renderLabel() ?>
					<?php echo $form['message']->render() ?>
				</div>
			</div>
            <div class="col"></div>
	        <strong class="champs_obligatoires">* Champs obligatoires</strong>
			<div class="ligne_btn" style="width: 425px;">
				<button type="submit" class="btn_suiv"><span>Envoyer</span></button>
			</div>
		</form>
		<?php endif; ?>
    </div>
</section>