<section id="contenu">
	<div id="creation_compte" style="width:70%; float: left;">
		<h1>Sélection des établissements</h1>
		<p>Accédez ici au formulaire de DRM, à l'historique de vos DRM ou demandez à procéder à une DRM rectificative</p>
		<span>Attention, vous n'avez plus que 4 jours pour soumettre votre DRM du mois en cours</span>
		<br /><br />
		<form action="<?php echo url_for('@tiers') ?>" method="post">
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->renderGlobalErrors(); ?>
			<?php echo $form['tiers']->render(array('onchange' => 'submit()')) ?>
		</form>
	</div>
	<?php include_partial('global/aides') ?>
	<div style="clear:both;">&nbsp;</div>
</section>