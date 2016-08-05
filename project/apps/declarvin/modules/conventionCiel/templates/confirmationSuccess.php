<script type="text/javascript">
function updateCompte() {
	$('#modification-adresse-form').show();
}
function closeCompte() {
	$('#modification-adresse-form').hide();
}
</script>
<section id="contenu" style="padding: 30px 20px 70px;">
	<div id="creation_compte" class="popup_form">
		<h1>Confirmation</h1>
		<?php if ($sf_user->hasFlash('success')) : ?>
		    <p class="flash_notice"><?php echo $sf_user->getFlash('success'); ?></p>
                    <br />
		<?php endif; ?>
		<p class="txt-espace">Vous allez recevoir un email à l'adresse : <strong><?php echo $contrat->email ?></strong> contenant un contrat d'inscription à signer et à retourner par courrier à votre interprofession avec votre K-Bis.</p>
		<p class="txt-espace">En parallèle, votre interprofession vous fait parvenir le cas échéant par mail les avenants précisant les services disponibles, et notamment les contrats mandats de dépôts mis en place.</p>
		<p class="txt-espace">Dès que votre interprofession aura mis à jour vos données et votre compte, vous recevrez un autre mail afin de créer votre identifiant et votre mot de passe et d'activer définitivement votre compte.</p><br /><br />
		<p class="txt-espace">Si vous n'avez pas reçu d'email :</p>
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