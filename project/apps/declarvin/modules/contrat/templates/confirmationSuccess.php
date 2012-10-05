<script type="text/javascript">
function updateCompte() {
	$('#modification-adresse-form').show();
}
function closeCompte() {
	$('#modification-adresse-form').hide();
}
</script>
<section id="contenu">
	<div id="creation_compte" class="popup_form">
		<h1>Confirmation</h1>
		<?php if ($sf_user->hasFlash('success')) : ?>
		    <p class="flash_notice"><?php echo $sf_user->getFlash('success'); ?></p>
                    <br />
		<?php endif; ?>
		<p class="txt-espace">
                    Merci,<br />
                    Vous allez recevoir un e-mail à l'adresse <strong><?php echo $contrat->email ?></strong> contenant votre contrat en pièce jointe.<br />
                    Si vous n'avez pas reçu d'email :
                </p>
		<ul>
                    <li>Vérifiez vos spams</li>
                    <li>Vous vous êtes trompé dans vôtre adresse email : <a href="javascript:updateCompte()">Veuillez cliquer ici afin de la modifier.</a></li>
		</ul>
		<div id="modification-adresse-form" style="display: <?php echo ($showForm)? 'block' : 'none'; ?>;" >
                    <form id="creation_compte" method="post" action="<?php echo url_for('contrat_etablissement_confirmation') ?>">
                        <h2>Modification de votre adresse email :</h2>
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
                    <a href="javascript:closeCompte()" class="supprimer">Supprimer</a>
		</div>
	</div>
</section>