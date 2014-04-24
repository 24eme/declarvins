<section id="contenu" style="padding: 30px 20px 70px;">
    <div id="creation_compte">
        <h1>Votre compte</h1>
		<p>Votre contrat <?php if ($contrat): ?>n°<?php echo $contrat->no_contrat ?><?php endif; ?> a déjà été validé et vous a été envoyé par e-mail.<br />Vous ne pouvez plus le modifier.<br />Merci de bien vouloir contacter votre interpro si cela vous pose problème et/ou de recréer un compte.</p>
        <div class="ligne_btn">
			<a href="<?php echo url_for('@contrat_nouveau') ?>" class="btn_valider"><span>Créer un compte</span></a>
		</div>
    </div>
</section>