<section id="contenu" style="padding: 30px 20px 70px;">
    <div id="creation_compte">
        
        <form id="creation_compte" method="post" action="<?php echo url_for('convention_habilitations') ?>">
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->renderGlobalErrors(); ?>
			<input id="contrat_nb_etablissement" type="hidden" name="nb_habilitation" value="<?php echo $nbHabilitation ?>" />
			<h1><strong>Étape 2 :</strong> Habilitations</h1>
			<div id="infos_etablissements">
				<h1>Utilisateurs</h1>
				<p class="intro">Veuillez ajouter les utilisateurs à habiliter aux téléprocédure CIEL</p>
				<?php 
					include_partial('templateHabilitation');
					include_partial('habilitation', array('indice' => 0, 'form' => $form['habilitations'][0], 'supprimer' => false));
					$i=0;
					foreach ($form['habilitations'] as $habilitation) {
						if ($i > 0) {
							include_partial('habilitation', array('indice' => $i, 'form' => $habilitation, 'supprimer' => true));
						}
						$i++; 
					}
				?>   
			</div>
			<strong class="champs_obligatoires">* Champs obligatoires</strong>
			<div class="ligne_btn">
	            <a href="#" class="btn_ajouter" id="ajouter_etablissement">Ajouter <span>un utilisateur</span></a>
			</div>
			<div class="ligne_btn">
				<button type="submit" class="btn_valider"><span>Suivant</span></button>
			</div>
		</form>

    </div>
</section>