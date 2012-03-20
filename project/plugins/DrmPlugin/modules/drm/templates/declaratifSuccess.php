<?php include_partial('global/navTop', array('active' => 'drm')); ?>
<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'declaratif', 'pourcentage' => '70')); ?>

    <section id="principal">
		<div id="application_dr">
			<h2>Veuillez maintenant déclarer ici les éléments suivants:</h2>
				
				<form id="declaratif_info" action="<?php echo url_for('drm_declaratif', $drm) ?>" method="post">
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Défaut d'apurement</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif alignes">
						<div class="col">
							<div class="ligne_form">
								<input type="radio" name="sans_defaut" id="sans_defaut">
								<label for="sans_defaut">Pas de défaut d'apurement</label>
							</div>
						</div>
						
						<div class="col">
							<div class="ligne_form">
								<input type="radio" name="defaut_apurement" id="defaut_apurement">
								<label for="defaut_apurement">Défaut d'apurement à déclarer (Joindre relevé de non apurement et copie du DAA)</label>
							</div>
						</div>
					</div>
					
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Mouvements au cours du mois</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif">
						<p class="intro">Documents prévalidés ou N° empreinte utilisés au cours du mois</p>
						
						<div class="champs_centres">
							<h3>DAA</h3>
							
							<div class="ligne_form">
								<label for="daa_du">du</label><input type="text" name="daa_du" id="daa_du">
								<label for="daa_au">au</label><input type="text" name="daa_au" id="daa_au">
							</div>
							
							<h3>DSA</h3>
							
							<div class="ligne_form">
								<label for="dsa_du">du</label><input type="text" name="dsa_du" id="dsa_du">
								<label for="dsa_au">au</label><input type="text" name="dsa_au" id="dsa_au">
							</div>
						</div>
						
						<div class="ligne_form ligne_entiere">
							<label for="num_empreinte">N° empreinte machine à timbrer</label><input type="text" name="num_empreinte" id="num_empreinte">
						</div>
						<div class="ligne_form ligne_entiere ecart_check">
							<input type="checkbox" name="adhesion" id="adhesion"><label for="adhesion">Adhésion à EMCS/GAMMA (n° non nécessaires)</label>
						</div>
					</div>
					
					<ul class="onglets_declaratif">
						<li class="actif"><strong>Caution</strong></li>
					</ul>
					
					<div class="contenu_onglet_declaratif alignes">
						<div class="ligne_form" id="caution_accepte">
							<input type="radio" name="caution" id="caution_organisme">
							<label for="caution_organisme">Oui, organisme</label>
							<input type="text" name="organisme" id="organisme">
						</div>
						
						<div class="ligne_form">
							<input type="radio" name="caution" id="dispense">
							<label for="dispense">Dispense</label>
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