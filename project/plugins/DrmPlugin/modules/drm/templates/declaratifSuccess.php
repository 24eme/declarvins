<?php include_partial('global/navTop', array('active' => 'drm')); ?>
<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'declaratif', 'pourcentage' => '70')); ?>

    <section id="principal">
		<div id="application_dr">
			<h2>Veuillez maintenant déclarer ici les éléments suivants:</h2>
				
				<form id="declaratif_info" action="<?php echo url_for('drm_declaratif', $drm) ?>" method="post">
			        <?php echo $form->renderGlobalErrors() ?>
					<?php echo $form->renderHiddenFields() ?>
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Défaut d'apurement</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif alignes">
						<div class="col">
							<div class="ligne_form">
								<?php echo $form['apurement']->render() ?>
								<?php echo $form['apurement']->renderError() ?>
							</div>
						</div>
						
						<!-- <div class="col">
							<div class="ligne_form">
								<input type="radio" name="defaut_apurement" id="defaut_apurement">
								<label for="defaut_apurement">Défaut d'apurement à déclarer (Joindre relevé de non apurement et copie du DAA)</label>
							</div>
						</div>  -->
					</div>
					
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Mouvements au cours du mois</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif">
						<p class="intro">Documents prévalidés ou N° empreinte utilisés au cours du mois</p>
						
						<div class="champs_centres">
							<h3>DAA</h3>
							
							<div class="ligne_form">
								<?php echo $form['daa_debut']->renderLabel() ?><?php echo $form['daa_debut']->render() ?><?php echo $form['daa_debut']->renderError() ?>
								<?php echo $form['daa_fin']->renderLabel() ?><?php echo $form['daa_fin']->render() ?><?php echo $form['daa_fin']->renderError() ?>
							</div>
							
							<h3>DSA</h3>
							
							<div class="ligne_form">
								<?php echo $form['dsa_debut']->renderLabel() ?><?php echo $form['dsa_debut']->render() ?><?php echo $form['dsa_debut']->renderError() ?>
								<?php echo $form['dsa_fin']->renderLabel() ?><?php echo $form['dsa_fin']->render() ?><?php echo $form['dsa_fin']->renderError() ?>
							</div>
						</div>
						<div class="ligne_form ligne_entiere ecart_check">
							<?php echo $form['adhesion_emcs_gamma']->render() ?><?php echo $form['adhesion_emcs_gamma']->renderLabel() ?><?php echo $form['adhesion_emcs_gamma']->renderError() ?>
						</div>
					</div>
					
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Caution</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif alignes">
						<div class="ligne_form" id="caution_accepte">
							<?php echo $form['caution']->render() ?>
							<?php echo $form['caution']->renderError() ?>
						</div>
							
						<div class="ligne_form" id="organisme">
							<?php echo $form['organisme']->renderLabel() ?><?php echo $form['organisme']->render() ?><?php echo $form['organisme']->renderError() ?>
						</div>
					</div>
					
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Paiement</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif alignes">
						<div class="ligne_form">
							<?php echo $form['moyen_paiement']->renderLabel() ?><?php echo $form['moyen_paiement']->render() ?><?php echo $form['moyen_paiement']->renderError() ?>
						</div>
					</div>
					
					<div id="btn_etape_dr">
						<a href="<?php echo url_for('drm_vrac', array('sf_subject' => $drm, 'precedent' => '1')) ?>" class="btn_prec"><span>Précédent</span></a>
						<button type="submit" class="btn_suiv"><span>suivant</span></button>
					</div>
				</form>
		</div>
	</section>
</section>