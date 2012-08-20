<?php include_component('global', 'nav', array('active' => 'vrac')); ?>

<section id="contenu" class="vracs vrac_validation">
	<div class="popup_form" id="recap_saisie">
		<?php if ($sf_user->hasFlash('termine')): ?>
			<h2>La saisie est terminée !</h2>
		<?php endif; ?>
       	<h2>
			N° d'enregistrement du contrat : 
			<span><?php echo $vrac->numero_contrat ?></span>
		</h2>

		 <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'vrac' => $vrac)); ?>
	</div>
	<div class="ligne_form_btn">
		<?php if ($etablissement): ?>
			<a href="<?php echo url_for("vrac_etablissement", array('identifiant' => $etablissement->identifiant)) ?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
		<?php else: ?>
			<a href="<?php echo url_for("vrac_admin") ?>?>" class="etape_prec"><span>Retour à liste des contrats</span></a>
		<?php endif; ?>
	</div>
</section>