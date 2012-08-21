
	<form class="popup_form" id="recap_saisie" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<h2>Récapitulatif de la saisie</h2>
		 <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac,'etablissement' => $etablissement, 'vrac' => $form->getObject(), 'editer_etape' => true)); ?>
		<div class="ligne_form_btn">
			<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>		
			<button class="valider_etape" type="submit"><span>Terminer la saisie</span></button>
		</div>
	</form>