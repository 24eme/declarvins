<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>

<section id="contenu">
	<div id="creation_compte">
		<h1>Confirmation</h1>
		<?php if ($sf_user->hasFlash('success')) : ?>
		    <p class="flash_notice"><?php echo $sf_user->getFlash('success'); ?></p>
            <br />
		<?php endif; ?>
		<p class="txt-espace">Vous allez recevoir un email à l'adresse : <strong><?php echo $compte->email ?></strong> contenant la convention d'adhésion au service CIEL à signer et à retourner par courrier à votre service douane et votre interprofession.</p>
		<p class="txt-espace">Si vous n'avez pas reçu d'email :</p>
		<ul>
        	<li>Vérifiez vos spams</li>
		</ul>
	</div>
</section>