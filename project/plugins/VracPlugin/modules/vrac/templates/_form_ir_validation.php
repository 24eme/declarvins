<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
	<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>
	<form class="popup_form" id="recap_saisie" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<h2>Récapitulatif de la saisie</h2>
		 <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac, 'vrac' => $form->getObject())); ?>
		<div class="ligne_form_btn">
			<a href="" class="etape_prec"><span>etape précédente</span></a>		
			<button class="valider_etape" type="submit"><span>Terminer la saisie</span></button>
		</div>
	</form>
</section>