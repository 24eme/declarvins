<?php 
    use_helper('Float');
	$warningMiseEnMarche = false;
	$warningExport = false;
	if (!$form->getObject()->isValide() && $form->getObject()->premiere_mise_en_marche && $form->getObject()->vendeur->famille == EtablissementFamilles::FAMILLE_NEGOCIANT) {
		$warningMiseEnMarche = true;
	} 
	if (!$form->getObject()->isValide() && $form->getObject()->acheteur->pays && !preg_match("/^fr/i", $form->getObject()->acheteur->pays)) {
		$warningExport = true;
	} 
?>
				<?php if($warningMiseEnMarche || $warningExport): ?>
            	<div class="vigilance_list">
				    <h3>Points de vigilance</h3>
				    <ol>
				    	<?php if ($warningMiseEnMarche): ?><li>Attention, vous êtes sur le point de valider un contrat de première mise en marché.</li><?php endif; ?>
				    	<?php if ($warningExport): ?><li>Attention, vous êtes sur le point de valider un contrat pour le marché français (étape &laquo;Marché&raquo;, champs &laquo;Expédition export&raquo;).</li><?php endif; ?>
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
		<?php 
		if ($form->getObject()->hasOioc() && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
		?>
		<div style="background: none repeat scroll 0 0 #ECFEEA;border: 1px solid #359B02;color: #1E5204;font-weight: bold;text-align: left;margin: 10px 0 10px 0;padding: 5px 10px;">
			<ul>
				<li>
					Une fois le contrat validé par toutes les parties, votre déclaration de transaction sera envoyée automatiquement à votre OIOC.<br />Si la date de chargement Oco n'est pas renseignée sur votre contrat en ligne dans les 24 heures ouvrées pour AVPI, 48 heures ouvrées pour QUALISUD, vous devrez impérativement prendre contact avec votre OIOC afin de transmettre vous-même votre déclaration de transaction (« PDF Transaction »).<br />Votre interprofession ne pourra être tenu responsable de la non réception de votre déclaration de transaction par votre OIOC.
				</li>
			</ul>
		</div>
	        		<div class="vracs_ligne_form" style="font-weight: bold;">
	            		<?php echo $form['transaction']->renderError() ?>
	            		<?php echo $form['transaction']->render() ?>
	            		<?php echo $form['transaction']->renderLabel() ?>
	        		</div>
		<?php 
			}
		?>
		<?php 
		if ($form->getObject()->has_transaction && !$form->getObject()->hasOioc() && !$sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)):
		?>
		<div style="border: 1px solid #ff9f00;color: #3e3e3e;font-weight: bold;text-align: left;margin: 10px 0 10px 0;padding: 5px 10px;">
			<ul>
				<li style="text-align: center;">
					vous devez imprimer ou enregistrer votre declaration de transaction et l'envoyer à votre organisme d'inspection
				</li>
			</ul>
		</div>
		<?php endif; ?>
		<div class="ligne_form_btn">
			<a href="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => 'clause', 'etablissement' => $etablissement)) ?>" class="etape_prec"><span>etape précédente</span></a>
			<button id="brouillon" style="text-transform: uppercase; color: #FFFFFF; height: 21px; line-height: 21px; font-weight: bold; padding: 0 10px; background-color: #989898; border: 1px solid  #ECEBEB;" type="submit"><span>Sauvegarder le brouillon</span></button>
			<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$form->getObject()->isRectificative()): ?>
				<button id="no_mail" style="text-transform: uppercase; color: #FFFFFF; height: 21px; line-height: 21px; font-weight: bold; padding: 0 10px; background-color: #FF9F00; border: 1px solid #D68500;" type="submit"><span>Valider sans e-mail</span></button>
			<?php endif; ?>
			<button id="btnSubmit" class="valider_etape" type="submit"><span>Terminer la saisie</span></button>
		</div>
        <div class="ligne_form_btn">
            <a href="<?php echo url_for('vrac_supprimer', array('sf_subject' => $form->getObject(), 'etablissement' => $etablissement)) ?>" class="annuler_saisie" onclick="return confirm('<?php if ($form->getObject()->hasVersion()): ?>Attention, vous êtes sur le point d\'annuler les modifications en cours<?php else: ?>Attention, ce contrat sera supprimé de la base<?php endif; ?>')"><span><?php if($form->getObject()->hasVersion()): ?>Annuler les modifications<?php else: ?>supprimer le contrat<?php endif; ?></span></a>
        </div> 
	</form>
	<script type="text/javascript">
		var showConfirme = true;
		$("#no_mail").click(function() {
			showConfirme = false;
			$('#<?php echo $form['email']->renderId() ?>').val(0);
			$("#recap_saisie").submit();
			return false;
		});
		$("#brouillon").click(function() {
			showConfirme = false;
			$('#<?php echo $form['brouillon']->renderId() ?>').val(1);
			$("#recap_saisie").submit();
			return false;
		});
		$("#brouillon").click(function() {
			showConfirme = false;
			$("#recap_saisie").submit();
			return false;
		});


		$('#recap_saisie').submit(function() {
			if (showConfirme)
		    	return confirm("Vous êtes sur le point de valider un contrat <?php echo $form->getObject()->type_transaction ?>\n\nde <?php echoArialFloat($form->getObject()->getTotalUnitaire()) ?> € HT / <?php if($form->getObject()->type_transaction != 'raisin'): ?>HL<?php else: ?>Kg<?php endif; ?> pour <?php echoArialFloat($form->getObject()->volume_propose) ?> <?php if($form->getObject()->type_transaction != 'raisin'): ?>HL<?php else: ?>Kg<?php endif; ?> proposé\n\nSoit un prix total de <?php echoArialFloat($form->getObject()->getPrixTotalCalc()) ?> € HT");
			else
				return true;
		});

				
	</script>