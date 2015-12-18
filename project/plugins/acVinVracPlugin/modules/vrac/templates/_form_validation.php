
            	<?php if (!$form->getObject()->isValide() && $form->getObject()->premiere_mise_en_marche && $form->getObject()->vendeur->famille == EtablissementFamilles::FAMILLE_NEGOCIANT): ?>
            	<div class="vigilance_list">
				    <h3>Points de vigilance</h3>
				    <ol>
				    	<li>Attention, vous êtes sur le point de valider un contrat de première mise en marché.</li>
				    </ol>
				</div>
				<?php endif; ?>
	<form class="popup_form" id="recap_saisie" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape, 'etablissement' => $etablissement)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		
		<div class="bloc_form_commentaire bloc_form ">
	        <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
	        	<?php if ($form->getObject()->exist('observations') && $form->getObject()->observations): ?>
                <div style="background: #E3E2E2; color: #3E3E3E; border-radius: 5px;">
                    <div style="padding: 4px 10px 10px 10px;">
                		<label style="padding: 10px 0px; font-weight: bold; display: block;">Observations</label>
                		<pre style="background: #fff; padding: 8px 0;"><?php echo $form->getObject()->observations ?></pre>
            		</div>
                </div>
                <?php endif; ?>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <?php echo $form['commentaires']->renderError() ?>
	            <?php echo $form['commentaires']->renderLabel() ?>
	            <?php echo $form['commentaires']->render() ?>
	        </div>
	        <?php else: ?>
	        <div class="vracs_ligne_form vracs_ligne_form_alt">
	            <?php echo $form['observations']->renderError() ?>
	            <?php echo $form['observations']->renderLabel() ?>
	            <?php echo $form['observations']->render(array("maxlength" => "250")) ?><br /><br />250 caractères max.
	        </div>
	    <?php endif; ?>
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

		<div class="ligne_form_btn">
			<?php if($form->getObject()->has_transaction): ?>
				<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'transaction', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<?php else: ?>
				<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'condition', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<?php endif; ?>
			<button id="brouillon" style="text-transform: uppercase; color: #FFFFFF; height: 21px; line-height: 21px; font-weight: bold; padding: 0 10px; background-color: #989898; border: 1px solid  #ECEBEB;" type="submit"><span>Sauvegarder le brouillon</span></button>
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
		$("#brouillon").click(function() {
			$('#<?php echo $form['brouillon']->renderId() ?>').val(1);
			$("#recap_saisie").submit();
			return false;
		});
	</script>