
	<form class="popup_form" id="recap_saisie" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		
		<div class="bloc_form_commentaire bloc_form ">
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <?php echo $form['commentaires']->renderError() ?>
	            <?php echo $form['commentaires']->renderLabel() ?>
	            <?php echo $form['commentaires']->render() ?>
	        </div>
	    </div>
	    <br />
	    <h2>Récapitulatif de la saisie</h2>
	    <?php include_partial('showContrat', array('configurationVrac' => $configurationVrac,'etablissement' => $etablissement, 'vrac' => $form->getObject(), 'editer_etape' => true)); ?>
		<div class="ligne_form_btn">
			<?php if($form->getObject()->has_transaction): ?>
				<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<?php else: ?>
				<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<?php endif; ?>
			<button class="valider_etape" type="submit"><span>Terminer la saisie</span></button>
		</div>
	</form>