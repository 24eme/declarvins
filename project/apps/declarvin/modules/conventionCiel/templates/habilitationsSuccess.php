<?php include_component('global', 'navTop', array('active' => 'ciel')); ?>
<style>
#creation_compte #infos_etablissements .etablissement {
width: 275px;
margin: 0 10px 0 0;
padding: 5px;
}
#creation_compte #infos_etablissements .etablissement .ligne_form label {
width: 131px;
}
#creation_compte .ligne_form input[type="text"], #creation_compte .ligne_form input[type="password"], #creation_compte .ligne_form select {
	width: 131px;
}
#creation_compte .ligne_btn {
	margin: 10px 0 0 0 !important;
}
#creation_compte #infos_etablissements {
	margin: 15px 0;
}
</style>
<section id="contenu">
    <div id="creation_compte">
        
        <form id="creation_compte" method="post" action="<?php echo url_for('convention_habilitations', $etablissement) ?>">
			<input id="contrat_nb_etablissement" type="hidden" name="nb_habilitation" value="<?php echo $nbHabilitation ?>" />
			<h1><strong>Étape 2 :</strong> Habilitations des comptes</h1>			
			<?php echo $form->renderHiddenFields(); ?>
			<?php echo $form->renderGlobalErrors(); ?>
			<div id="infos_etablissements">
				<p class="intro">Veuillez ajouter les utilisateurs à habiliter aux téléprocédure CIEL</p>
				<?php 
					$i=0;
					foreach ($form['habilitations'] as $habilitation) {
						include_partial('habilitation', array('indice' => $i, 'form' => $habilitation));
						$i++; 
					}
				?>   
			</div>
			<div style="text-align: right;">
				<strong class="champs_obligatoires">* Champs obligatoires</strong>
			</div>
			<div class="ligne_btn">
				<a href="<?php echo url_for('convention_nouveau', $etablissement) ?>" class="btn_prec"><span>Précédent</span></a>
				<button type="submit" class="btn_suiv"><span>Suivant</span></button>
			</div>
		</form>

    </div>
</section>