<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<style>
#principal .tableau_ajouts_liquidations table tbody td input[type="text"], #principal .tableau_resultats table tbody td input[type="text"] {
	width: 45px !important;
	text-align: right !important;
}
</style>

<section id="contenu" class="drm_vracs">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'crd', 'pourcentage' => '70')); ?>



    <section id="principal">
        <p>Veuillez indiquer ci-dessous le compte des capsules CRD correspondant aux volumes déclarés :</p>
        <br />
        <div id="application_dr" class="tableau_ajouts_liquidations">
            <form action="<?php echo url_for('drm_crd', $drm) ?>" method="post">
            	<?php echo $form->renderHiddenFields(); ?>
	            <?php echo $form->renderGlobalErrors(); ?>
	            
				<table id="lignes_crd" class="tableau_recap">
					<thead>
                    	<tr>
                        	<th rowspan="2">Catégorie fiscale</th>
                            <th rowspan="2" style="width: 115px">Stock théorique Début de mois</th>
                            <th colspan="3" style="width: 250px">Entrées</th>
                            <th colspan="3" style="width: 250px">Sorties</th>
                            <th rowspan="2" style="width: 115px">Stock théorique Fin de mois</th>
                       </tr>
                    	<tr>
                        	<th style="width: 70px; text-align: center;">Achats<br /><a class="msg_aide" title="Message aide" data-msg="" href=""></a></th>
                            <th style="width: 70px">Excédents<br /><a class="msg_aide" title="Message aide" data-msg="" href=""></a></th>
                            <th style="width: 70px">Retours<br /><a class="msg_aide" title="Message aide" data-msg="" href=""></a></th>
                            <th style="width: 70px">Utilisées<br /><a class="msg_aide" title="Message aide" data-msg="" href=""></a></th>
                            <th style="width: 70px">Détruites<br /><a class="msg_aide" title="Message aide" data-msg="" href=""></a></th>
                            <th style="width: 70px">Manquantes<br /><a class="msg_aide" title="Message aide" data-msg="" href=""></a></th>
                       </tr>
                   </thead>
                   <tbody>
                   		
                       <?php $i=0; foreach ($form['crds'] as $key => $subform): ?>
                   		<tr<?php if ($i%2): ?> class="alt"<?php endif; ?>>
                   			<td><?php echo $drm->crds->get($key)->libelle; ?></td>
                   			<td><?php echo (isset($form['crds'][$key]['total_debut_mois']))? $form['crds'][$key]['total_debut_mois'] : $drm->crds->get($key)->total_debut_mois;?><input type="hidden" value="<?php echo $drm->crds->get($key)->total_debut_mois; ?>" class="entrees" /></td>
                   			<td><?php echo $form['crds'][$key]['entrees']['achats']->render(array('class' => 'entrees')) ?></td>
                   			<td><?php echo $form['crds'][$key]['entrees']['excedents']->render(array('class' => 'entrees')) ?></td>
                   			<td><?php echo $form['crds'][$key]['entrees']['retours']->render(array('class' => 'entrees')) ?></td>
                   			<td><?php echo $form['crds'][$key]['sorties']['utilisees']->render(array('class' => 'sorties')) ?></td>
                   			<td><?php echo $form['crds'][$key]['sorties']['detruites']->render(array('class' => 'sorties')) ?></td>
                   			<td><?php echo $form['crds'][$key]['sorties']['manquantes']->render(array('class' => 'sorties')) ?></td>
                   			<td class="total_crd"><?php echo $drm->crds->get($key)->total_fin_mois; ?></td>
                   		</tr>
                   		<?php $i++; endforeach; ?>
				   </tbody>
				</table>
				<div class="btn" style="text-align: right;">
					 <a href="<?php echo url_for('drm_crd_product_ajout', $drm) ?>" class="btn_ajouter btn_popup" data-popup="#popup_ajout_crd" data-popup-config="configForm">Ajouter une CRD</a>
				</div>
	            <br /><br />
	            <div id="btn_etape_dr">
                	<?php if (!$drm->declaration->hasMouvementCheck()): ?>
                	<a href="<?php echo url_for('drm_mouvements_generaux', $drm) ?>" class="btn_prec"><span>Précédent</span></a>
                	<?php else: ?>
                    <a href="<?php echo url_for('drm_vrac', array('sf_subject' => $drm, 'precedent' => '1')) ?>" class="btn_prec"><span>Précédent</span></a>
                    <?php endif; ?>
	                <button type="submit" class="btn_suiv"><span>Suivant</span></button>
	            </div>
	
	            <div class="ligne_btn">
	                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
	            </div>

            </form>
        </div>
    </section>
</section>

