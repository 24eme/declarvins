<?php use_helper('Version'); ?>
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


	<?php include_partial('drm/controlMessage'); ?>
    <section id="principal">
        <div id="application_dr" class="tableau_ajouts_liquidations">
            <form action="<?php echo url_for('drm_crd', $drm) ?>" method="post">
            	<?php echo $form->renderHiddenFields(); ?>
	            <?php echo $form->renderGlobalErrors(); ?>


	            <div id="btn_etape_dr">
                    <button type="submit" name="prev" value="1" class="btn_prec"><span>Précédent</span></button>
	                <button type="submit" class="btn_suiv"><span>Suivant</span></button>
	            </div>

		        <p>Veuillez indiquer ci-dessous le compte des capsules CRD correspondant aux volumes déclarés <a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_centilisations" href=""></a>:</p>
		        <br />

				<table id="lignes_crd" class="tableau_recap">
					<thead>
                    	<tr>
                        	<th rowspan="2">Catégorie fiscale</th>
                            <th rowspan="2" style="width: 115px">Stock théorique Début de mois</th>
                            <th colspan="4" style="width: 250px">Entrées</th>
                            <th colspan="4" style="width: 250px">Sorties</th>
                            <th rowspan="2" style="width: 115px">Stock théorique Fin de mois</th>
                       </tr>
                    	<tr>
                        	<th style="width: 70px; text-align: center; padding:0;">Achats<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_achats" href=""></a></th>
                            <th style="width: 70px;padding:0;">Excédents<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_excedents" href=""></a></th>
                            <th style="width: 70px;padding:0;">Retours<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_retours" href=""></a></th>
                            <th style="width: 70px;padding:0;">Autres<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_entreeautres" href=""></a></th>
                            <th style="width: 70px;padding:0;">Utilisées<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_utilisees" href=""></a></th>
                            <th style="width: 70px;padding:0;">Détruites<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_detruites" href=""></a></th>
                            <th style="width: 70px;padding:0;">Manquantes<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_manquantes" href=""></a></th>
                            <th style="width: 70px;padding:0;">Autres<br /><a class="msg_aide" title="Message aide" data-msg="help_popup_drm_crd_sortieautres" href=""></a></th>
                       </tr>
                   </thead>
                   <tbody>

                       <?php $i=0; foreach ($form['crds'] as $key => $subform): ?>
                   		<tr<?php if ($i%2): ?> class="alt"<?php endif; ?>>
                   			<td>
                   				<?php if (!$drm->crds->get($key)->total_debut_mois): ?>
                   				<a class="supprimer" style="left: 5px;" href="<?php echo url_for('drm_crd_product_delete', array('sf_subject' => $drm, 'id' => $key)) ?>">Supprimer</a>
                   				<?php endif; ?>
                   				<?php echo $drm->crds->get($key)->libelle; ?>
                   			</td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key), 'total_debut_mois') ?>"><?php echo $form['crds'][$key]['total_debut_mois'] ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->entrees, 'achats') ?>"><?php echo $form['crds'][$key]['entrees']['achats']->render(array('class' => 'entrees')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->entrees, 'excedents') ?>"><?php echo $form['crds'][$key]['entrees']['excedents']->render(array('class' => 'entrees')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->entrees, 'retours') ?>"><?php echo $form['crds'][$key]['entrees']['retours']->render(array('class' => 'entrees')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->entrees, 'autres') ?>"><?php echo $form['crds'][$key]['entrees']['autres']->render(array('class' => 'entrees')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->sorties, 'utilisees') ?>"><?php echo $form['crds'][$key]['sorties']['utilisees']->render(array('class' => 'sorties')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->sorties, 'detruites') ?>"><?php echo $form['crds'][$key]['sorties']['detruites']->render(array('class' => 'sorties')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->sorties, 'manquantes') ?>"><?php echo $form['crds'][$key]['sorties']['manquantes']->render(array('class' => 'sorties')) ?></td>
                   			<td style="padding:0;" class="<?php echo isVersionnerCssClass($drm->crds->get($key)->sorties, 'autres') ?>"><?php echo $form['crds'][$key]['sorties']['autres']->render(array('class' => 'sorties')) ?></td>
                   			<td style="padding:0;" class="total_crd <?php echo isVersionnerCssClass($drm->crds->get($key), 'total_fin_mois') ?>"><?php echo $drm->crds->get($key)->total_fin_mois; ?></td>
                   		</tr>
                   		<?php $i++; endforeach; ?>
				   </tbody>
				</table>
				<div class="btn" style="text-align: right;">
					 <a href="<?php echo url_for('drm_crd_product_ajout', $drm) ?>" class="btn_ajouter btn_popup" data-popup="#popup_ajout_crd" data-popup-config="configForm" data-popup-enregistrement-crd="true">Ajouter une CRD</a>
				</div>
	            <br /><br />
	            <div id="btn_etape_dr">
                    <button type="submit" name="prev" value="1" class="btn_prec"><span>Précédent</span></button>
	                <button type="submit" class="btn_suiv"><span>Suivant</span></button>
	            </div>

				<?php if($drm->isRectificative() && $drm->exist('ciel') && $drm->ciel->transfere): ?>
				<?php else: ?>
	            <div class="ligne_btn">
	                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
	            </div>
				<?php endif; ?>
            </form>
        </div>
    </section>
</section>

