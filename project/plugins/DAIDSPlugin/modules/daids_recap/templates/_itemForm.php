<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>

<div id="col_recolte_<?php echo $form->getObject()->renderId() ?>" class="col_recolte<?php if ($active): ?> col_active<?php endif; ?>" data-input-focus="#drm_detail_entrees_achat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? versionnerCssClass() : '' ?>">
    <form action="<?php echo url_for('daids_recap_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->renderId() ?>"></a>
        <h2><?php echo $form->getObject()->getCouleur()->getConfig()->libelle ?></h2>
        <div class="col_cont">
            <?php if($config_lieu->hasCepage()): ?>
            <p class="cepage"><?php echo $form->getObject()->getCepage()->getConfig()->libelle ?></p>
            <?php endif; ?>
            <p class="label" style="font-size: 12px; text-align: center;">
                <?php echo $form->getObject()->getLabelsLibelle() ?><br />
                <?php echo $form->getObject()->label_supplementaire ?>
            </p>
            
            <p class="stock_th stock_th_drm  <?php echo isVersionnerCssClass($form->getObject(), 'stock_theorique') ?>">
            	<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
            	<?php echo $form['stock_theorique']->render(array('class' => 'num num_float num_light', 'data-val-defaut' => sprintFloat($form->getObject()->stock_theorique, "%01.04f"))) ?>
				<?php else: ?>
            	<?php echo $form['stock_theorique']->render(array('readonly' => 'readonly', 'class' => 'texte stock_th stock_th_drm', 'data-val-defaut' => sprintFloat($form->getObject()->stock_theorique, "%01.04f"))) ?>
            	<?php endif; ?>
			</p>     	
			
			<div class="groupe" data-groupe-id="1">
				<p class="total_chais <?php echo isVersionnerCssClass($form->getObject(), 'stock_chais') ?>">
					<?php echo $form['stock_chais']->render(array('class' => 'texte total_chais', 'data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['chais']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['propriete_tiers']->renderId(), 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->stock_chais, "%01.04f"))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'chais') ?>"><?php echo $form['stocks']['inventaire_chais']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-liaison' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['physique_chais']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['chais']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks->chais, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'chais') ?>"><?php echo $form['chais_details']['entrepot_a']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->chais_details->entrepot_a, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'chais') ?>"><?php echo $form['chais_details']['entrepot_b']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->chais_details->entrepot_b, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'chais') ?>"><?php echo $form['chais_details']['entrepot_c']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->chais_details->entrepot_c, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'propriete_tiers') ?>"><?php echo $form['stocks']['propriete_tiers']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks->propriete_tiers, "%01.04f"))) ?></li>
				</ul>
			</div>
			
			<div class="groupe" data-groupe-id="2">
				<p class="total_propriete <?php echo isVersionnerCssClass($form->getObject(), 'stock_propriete') ?>">
					<?php echo $form['stock_propriete']->render(array('class' => 'texte total_propriete', 'data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['chais']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['tiers']->renderId(), 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->stock_propriete, "%01.04f"))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'chais') ?>"><?php echo $form['stocks']['physique_chais']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-liaison' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['inventaire_chais']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks']['chais']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks->chais, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'tiers') ?>"><?php echo $form['stocks']['tiers']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks->tiers, "%01.04f"))) ?></li>
				</ul>
			</div>

			<div class="groupe" data-groupe-id="3">
				<p class="total_propriete"></p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'reserve') ?>"><?php echo $form['stock_propriete_details']['reserve']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->reserve, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'conditionne') ?>"><?php echo $form['stock_propriete_details']['conditionne']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->conditionne, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'vrac_vendu') ?>"><?php echo $form['stock_propriete_details']['vrac_vendu']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->vrac_vendu, "%01.04f"))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'vrac_libre') ?>"><?php echo $form['stock_propriete_details']['vrac_libre']->render(array('data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stock_propriete']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stock_propriete_details']['reserve']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stock_propriete_details']['vrac_vendu']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stock_propriete_details']['conditionne']->renderId(), 'data-calcul' => 'diff', 'class' => 'texte', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->vrac_libre, "%01.04f"))) ?></li>
				</ul>
			</div>

			<p class="total_manq_exce <?php echo isVersionnerCssClass($form->getObject(), 'total_manquants_excedents') ?>">
				<?php echo $form['total_manquants_excedents']->render(array('class' => 'texte total_manq_exce', 'data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stock_chais']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stock_theorique']->renderId(), 'data-calcul' => 'diff', 'data-val-defaut' => sprintFloat($form->getObject()->total_manquants_excedents, "%01.04f"))) ?>
	        </p>

			<p class="stock_th stock_th_mensuel <?php echo isVersionnerCssClass($form->getObject(), 'stock_mensuel_theorique') ?>">
				<?php echo $form['stock_mensuel_theorique']->render(array('class' => 'num num_float', 'data-val-defaut' => sprintFloat($form->getObject()->stock_mensuel_theorique, "%01.04f"))) ?>
	        </p>

			<div class="groupe" data-groupe-id="4">
				<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->vinifie, 'volume') ?>">
					<?php echo $form['stocks_moyen']['vinifie']['volume']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->vinifie->volume, "%01.04f"))) ?>
				</p>
				<ul id="choix_radio_<?php echo $form->getObject()->renderId() ?>_1" class="choix_radio">
					<?php echo $form['stocks_moyen']['vinifie']['taux']->render() ?>
					<li>
						<?php echo $form['stocks_moyen']['vinifie']['total']->render(array('data-radio-name' => '#choix_radio_'.$form->getObject()->renderId().'_1', 'class' => 'texte', 'data-calcul' => 'produit_radio', 'data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['vinifie']['volume']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->vinifie->total, "%01.04f"))) ?>
					</li>
				</ul>
			</div>

			<div class="groupe" data-groupe-id="5">
				<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->non_vinifie, 'volume') ?>">
					<?php echo $form['stocks_moyen']['non_vinifie']['volume']->render(array('class' => 'num num_float', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->non_vinifie->volume, "%01.04f"))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->conditionne, 'total') ?>">
						<?php echo $form['stocks_moyen']['non_vinifie']['total']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->non_vinifie->total, "%01.04f"))) ?>
					</li>
				</ul>
			</div>
			<?php if (isset($form['stocks_moyen']['conditionne'])): ?>
			<div class="groupe" data-groupe-id="6">
				<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->conditionne, 'volume') ?>">
					<?php echo $form['stocks_moyen']['conditionne']['volume']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->conditionne->volume, "%01.04f"))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->conditionne, 'total') ?>">
						<?php echo $form['stocks_moyen']['conditionne']['total']->render(array('class' => 'texte', 'data-calcul' => 'produit', 'data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['conditionne']['volume']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['conditionne']['taux']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->conditionne->total, "%01.04f"))) ?>
					</li>
				</ul>
			</div>
			<?php endif; ?>
			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_pertes_autorisees') ?>">
				<?php $dataChamps = (isset($form['stocks_moyen']['conditionne']))? '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['vinifie']['total']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['non_vinifie']['total']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['conditionne']['total']->renderId() : '#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['vinifie']['total']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['stocks_moyen']['non_vinifie']['total']->renderId(); ?>
				<?php echo $form['total_pertes_autorisees']->render(array('class' => 'texte', 'data-champs' => $dataChamps, 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->total_pertes_autorisees, "%01.04f"))) ?>
			</p>
			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_manquants_taxables') ?>">
				<?php echo $form['total_manquants_taxables']->render(array('class' => 'texte inverse_value not_null_value', 'data-champs' => '#col_recolte_'.$form->getObject()->renderId().' #'.$form['total_pertes_autorisees']->renderId().';#col_recolte_'.$form->getObject()->renderId().' #'.$form['total_manquants_excedents']->renderId(), 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->total_manquants_taxables, "%01.04f"))) ?>
			</p>

            <div class="col_btn">
                <button class="btn_valider" type="submit">Valider</button>
                <button class="btn_reinitialiser" type="submit">Annuler</button>
            </div>
        </div>
    </form>
</div>