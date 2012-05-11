<script type="text/javascript">
function updateCompte() {
	$('#form').show();
}
</script>
<section id="contenu">
	<div id="creation_compte">
		<h1>Confirmation</h1>
		<?php if ($sf_user->hasFlash('success')) : ?>
		    <p class="flash_message"><?php echo $sf_user->getFlash('success'); ?></p>
		<?php endif; ?>
		<p>Merci,<br />Vous allez recevoir un e-mail à l'adresse <strong><?php echo $contrat->email ?></strong> contenant votre contrat en pièce jointe.</p>
		<p>Si vous n'avez pas reçu d'email</p>
		<ul>
			<li>Vérifiez vos spams</li>
			<li>Vous vous êtes trompé dans vôtre adresse email : <a href="javascript:updateCompte()">Modifiez la</a></li>
		</ul>
		<div id="form" style="display: <?php echo ($showForm)? 'block' : 'none'; ?>;" >
		<form id="creation_compte" method="post" action="<?php echo url_for('contrat_etablissement_confirmation') ?>">
			<div class="col">
				<div class="ligne_form">
				<?php echo $form->renderHiddenFields(); ?>
				<?php echo $form['email']->renderError() ?>
				<?php echo $form['email']->renderLabel() ?>
				<?php echo $form['email']->render() ?>
				</div>
				<div class="ligne_form">
				<?php echo $form['email2']->renderError() ?>
				<?php echo $form['email2']->renderLabel() ?>
				<?php echo $form['email2']->render() ?>
				</div>
					
				<div class="ligne_btn">
					<button type="submit" class="btn_ajouter">Modifier</button>
				</div>
			</div>
		</form>
		</div>
	<div class="ligne_btn" style="text-align:left;">
		<a href="<?php echo url_for('contrat_pdf') ?>" class="button btn_valider">Pdf</a>
	</div>
	</div>
</section>