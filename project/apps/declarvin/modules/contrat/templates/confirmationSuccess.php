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
		Vous allez recevoir un email à l'adresse : <strong><?php echo $contrat->email ?></strong> contenant un contrat mandat à signer et à retourner par courrier à votre interprofession.<br />
		Dès que votre interprofession aura mis à jour vos données et votre compte, vous recevrez un second email afin de créer votre identifiant et votre mot de passe et d'activer définitivement votre compte.<br />
		Vous pouvez fermer cette page.<br /><br />
		Si vous n'avez pas reçu d'email :
		<ul>
        	<li>Vérifiez vos spams</li>
            <li>L'adresse mail indiqué plus haut est peut-être erronée, <a href="javascript:updateCompte()">Veuillez cliquer ici pour la modifier.</a></li>
		</ul>
		<div id="modification-adresse-form" style="display: <?php echo ($showForm)? 'block' : 'none'; ?>;" >
                    <form id="creation_compte" method="post" action="<?php echo url_for('contrat_etablissement_confirmation', array('send' => 'sended')) ?>">
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