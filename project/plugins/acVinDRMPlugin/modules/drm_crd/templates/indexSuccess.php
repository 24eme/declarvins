<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

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
	            
				<table class="tableau_recap">
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
                   		<tr>
                   			<td>1</td>
                   			<td>2</td>
                   			<td>3</td>
                   			<td>4</td>
                   			<td>5</td>
                   			<td>6</td>
                   			<td>7</td>
                   			<td>8</td>
                   			<td>9</td>
                   		</tr>
                   		<tr class="alt">
                   			<td>9</td>
                   			<td>8</td>
                   			<td>7</td>
                   			<td>6</td>
                   			<td>5</td>
                   			<td>4</td>
                   			<td>3</td>
                   			<td>2</td>
                   			<td>1</td>
                   		</tr>
				   </tbody>
				</table>
				<div class="btn" style="text-align: right;">
					 <a href="<?php echo url_for('drm_crd_product_ajout', $drm) ?>" class="btn_ajouter btn_popup" data-popup="#popup_ajout_crd" data-popup-config="configForm">Ajouter une CRD</a>
				</div>
	            <br /><br />
	            <div id="btn_etape_dr">
	                <a href="<?php echo url_for('drm_recap_redirect_last', $drm) ?>" class="btn_prec">
	                    <span>Précédent</span>
	                </a>
	                <button type="submit" class="btn_suiv"><span>Suivant</span></button>
	            </div>
	
	            <div class="ligne_btn">
	                <a href="<?php echo url_for('drm_delete_one', $drm) ?>" class="annuler_saisie btn_remise"><span>supprimer la drm</span></a>
	            </div>

            </form>
        </div>
    </section>
</section>

