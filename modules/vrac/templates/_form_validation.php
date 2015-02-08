
	<form class="popup_form" id="recap_saisie" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		
		<div class="bloc_form_commentaire bloc_form ">
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <?php echo $form['commentaires']->renderError() ?>
	            <?php echo $form['commentaires']->renderLabel() ?>
	            <?php echo $form['commentaires']->render() ?>
	        </div>
	    <?php if (isset($form['date_signature'])): ?>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <?php echo $form['date_signature']->renderError() ?>
	            <?php echo $form['date_signature']->renderLabel() ?>
	            <?php echo $form['date_signature']->render(array('class' => 'datepicker')) ?> (jj/mm/aaaa)
	        </div>
	    <?php endif; ?>
	    <?php if (isset($form['date_stats'])): ?>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <?php echo $form['date_stats']->renderError() ?>
	            <?php echo $form['date_stats']->renderLabel() ?>
	            <?php echo $form['date_stats']->render(array('class' => 'datepicker')) ?> (jj/mm/aaaa)
	        </div>
	    <?php endif; ?>
	    </div>
	    <br />
	    <h2>Récapitulatif de la saisie</h2>
	    <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac,'etablissement' => $etablissement, 'vrac' => $form->getObject(), 'editer_etape' => true)); ?>
	    <?php if ($form->getObject()->has_transaction): ?>
		<p style="text-align:right;">Assurez-vous de bien respecter les délais minimum de transmission de vos déclarations de transactions à votre organisme d’inspection/contrôle.</p>
		<?php endif; ?>
		<div class="ligne_form_btn">
			<?php if($form->getObject()->has_transaction): ?>
				<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<?php else: ?>
				<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<?php endif; ?>
			<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$form->getObject()->isRectificative()): ?>
				<button id="no_mail" style="text-transform: uppercase; color: #FFFFFF; height: 21px; line-height: 21px; font-weight: bold; padding: 0 10px; background-color: #FF9F00; border: 1px solid #D68500;" type="submit"><span>Valider sans e-mail</span></button>
			<?php endif; ?>
			<button class="valider_etape" type="submit"><span>Terminer la saisie</span></button>
		</div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('Attention, ce contrat sera supprimé de la base')"><span>supprimer le contrat</span></a>
        </div> 
	</form>
	<script type="text/javascript">
		$("#no_mail").click(function() {
			$('#<?php echo $form['email']->renderId() ?>').val(0);
			$("#recap_saisie").submit();
			return false;
		});
	</script>