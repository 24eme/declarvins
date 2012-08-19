<div id="contenu">
	<div id="rub_contrats">
		<section id="principal"> 
			<section id="contenu_etape">
			<h2>La saisie est terminée !</h2>
			<h2>N° d'enregistrement du contrat : <span><?php echo $vrac->numero_contrat ?></span></h2>
			<?php include_partial('showContrat', array('vrac' => $vrac, 'configurationVrac' => $configurationVrac)); ?>
			<div id="btn_etape_dr">
				<div class="btnValidation">
					<span>&nbsp;</span>
					<?php if($etablissement): ?>
						<a href="<?php echo url_for('vrac_etablissement', $etablissement) ?>" class="btn_majeur btn_gris">Retour à la liste des contrats</a>
					<?php else: ?>
						<a href="<?php echo url_for('vrac_admin') ?>" class="btn_majeur btn_gris">Retour à la liste des contrats</a>		
					<?php endif; ?>
				</div>
			</div>
			</section> 
		</section>
	</div>
</div>
