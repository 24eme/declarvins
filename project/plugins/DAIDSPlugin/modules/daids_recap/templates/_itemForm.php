<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>

<div id="col_recolte_<?php echo $form->getObject()->getKey() ?>" class="col_recolte<?php if ($active): ?> col_active<?php endif; ?>" data-input-focus="#drm_detail_entrees_achat" data-cssclass-rectif="<?php echo ($form->getObject()->getDocument()->isRectificative()) ? versionnerCssClass() : '' ?>">
    <form action="<?php echo url_for('daids_recap_update', $form->getObject()) ?>" method="post">
        <?php echo $form->renderHiddenFields(); ?>
        <a href="#" class="col_curseur" data-curseur="<?php echo $form->getObject()->getKey() ?>"></a>
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
				<?php echo $form['stock_theorique']->render(array('class' => 'texte stock_th stock_th_drm', 'data-val-defaut' => sprintFloat($form->getObject()->stock_theorique))) ?>
			</p>

			<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'chais') ?>">
				<?php echo $form['stocks']['chais']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks->chais))) ?>
			</p>
			<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'propriete_tiers') ?>">
				<?php echo $form['stocks']['propriete_tiers']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks->propriete_tiers))) ?>
			</p>
			<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks, 'tiers') ?>">
				<?php echo $form['stocks']['tiers']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks->tiers))) ?>
			</p>

			<p class="total_chais <?php echo isVersionnerCssClass($form->getObject(), 'stock_chais') ?>">
				<?php echo $form['stock_chais']->render(array('class' => 'texte total_chais', 'data-champs' => '#'.$form['stocks']['chais']->renderId().';#'.$form['stocks']['propriete_tiers']->renderId(), 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->stock_chais))) ?>
        	</p>

			<div class="groupe" data-groupe-id="1">
				<p class="total_propriete <?php echo isVersionnerCssClass($form->getObject(), 'stock_propriete') ?>">
					<?php echo $form['stock_propriete']->render(array('class' => 'texte total_propriete', 'data-champs' => '#'.$form['stocks']['chais']->renderId().';#'.$form['stocks']['tiers']->renderId(), 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->stock_propriete))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'reserve') ?>"><?php echo $form['stock_propriete_details']['reserve']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->reserve))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'vrac_vendu') ?>"><?php echo $form['stock_propriete_details']['vrac_vendu']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->vrac_vendu))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'vrac_libre') ?>"><?php echo $form['stock_propriete_details']['vrac_libre']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->vrac_libre))) ?></li>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stock_propriete_details, 'conditionne') ?>"><?php echo $form['stock_propriete_details']['conditionne']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->stock_propriete_details->getObject()->conditionne))) ?></li>
				</ul>
			</div>

			<p class="total_manq_exce <?php echo isVersionnerCssClass($form->getObject(), 'total_manquants_excedents') ?>">
				<?php echo $form['total_manquants_excedents']->render(array('class' => 'texte total_manq_exce', 'data-champs' => '#'.$form['stock_chais']->renderId().';#'.$form['stock_theorique']->renderId(), 'data-calcul' => 'diff', 'data-val-defaut' => sprintFloat($form->getObject()->total_manquants_excedents))) ?>
	        </p>

			<p class="stock_th stock_th_mensuel <?php echo isVersionnerCssClass($form->getObject(), 'stock_mensuel_theorique') ?>">
				<?php echo $form['stock_mensuel_theorique']->render(array('class' => 'texte stock_th stock_th_mensuel', 'data-val-defaut' => sprintFloat($form->getObject()->stock_mensuel_theorique))) ?>
	        </p>

			<div class="groupe" data-groupe-id="2">
				<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->vinifie, 'volume') ?>">
					<?php echo $form['stocks_moyen']['vinifie']['volume']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->vinifie->volume))) ?>
				</p>
				<ul id="choix_radio_<?php echo $form->getObject()->renderId() ?>_1" class="choix_radio">
					<?php echo $form['stocks_moyen']['vinifie']['taux']->render() ?>
					<li class="resultat">
						<?php echo $form['stocks_moyen']['vinifie']['total']->render(array('data-radio-name' => '#choix_radio_'.$form->getObject()->renderId().'_1', 'class' => 'texte', 'data-calcul' => 'produit_radio', 'data-champs' => '#'.$form['stocks_moyen']['vinifie']['volume']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->vinifie->total))) ?>
					</li>
				</ul>
			</div>

			<div class="groupe" data-groupe-id="3">
				<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->non_vinifie, 'volume') ?>">
					<?php echo $form['stocks_moyen']['non_vinifie']['volume']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->non_vinifie->volume))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->non_vinifie, 'total') ?>">
						<?php echo $form['stocks_moyen']['non_vinifie']['total']->render(array('class' => 'texte', 'data-calcul' => 'produit', 'data-champs' => '#'.$form['stocks_moyen']['non_vinifie']['volume']->renderId().';#'.$form['stocks_moyen']['non_vinifie']['taux']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->non_vinifie->total))) ?>
					</li>
				</ul>
			</div>

			<div class="groupe" data-groupe-id="4">
				<p class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->conditionne, 'volume') ?>">
					<?php echo $form['stocks_moyen']['conditionne']['volume']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->conditionne->volume))) ?>
				</p>
				<ul>
					<li class="<?php echo isVersionnerCssClass($form->getObject()->stocks_moyen->conditionne, 'total') ?>">
						<?php echo $form['stocks_moyen']['conditionne']['total']->render(array('class' => 'texte', 'data-calcul' => 'produit', 'data-champs' => '#'.$form['stocks_moyen']['conditionne']['volume']->renderId().';#'.$form['stocks_moyen']['conditionne']['taux']->renderId(), 'data-val-defaut' => sprintFloat($form->getObject()->stocks_moyen->conditionne->total))) ?>
					</li>
				</ul>
			</div>
			
			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_pertes_autorisees') ?>">
				<?php echo $form['total_pertes_autorisees']->render(array('class' => 'texte', 'data-champs' => '#'.$form['stocks_moyen']['vinifie']['total']->renderId().';#'.$form['stocks_moyen']['non_vinifie']['total']->renderId().';#'.$form['stocks_moyen']['conditionne']['total']->renderId(), 'data-calcul' => 'somme', 'data-val-defaut' => sprintFloat($form->getObject()->total_pertes_autorisees))) ?>
			</p>
			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_manquants_taxables') ?>">
				<?php echo $form['total_manquants_taxables']->render(array('class' => 'texte', 'data-champs' => '#'.$form['total_pertes_autorisees']->renderId().';#'.$form['total_manquants_excedents']->renderId(), 'data-calcul' => 'diff', 'data-val-defaut' => sprintFloat($form->getObject()->total_manquants_taxables))) ?>
			</p>
			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_droits') ?>">
				<?php echo $form['total_droits']->render(array('class' => 'texte', 'data-champs' => '#'.$form['total_manquants_taxables']->renderId().';#'.$form['douane']['taux']->renderId(), 'data-calcul' => 'produit', 'data-val-defaut' => sprintFloat($form->getObject()->total_droits))) ?>
			</p>
			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_regulation') ?>">
				<?php echo $form['total_regulation']->render(array('class' => 'num num_float', 'autocomplete' => 'off', 'data-val-defaut' => sprintFloat($form->getObject()->total_regulation))) ?>
			</p>

			<p class="<?php echo isVersionnerCssClass($form->getObject(), 'total_droits_regulation') ?>">
				<?php echo $form['total_droits_regulation']->render(array('class' => 'texte', 'data-champs' => '#'.$form['total_droits']->renderId().';#'.$form['total_regulation']->renderId(), 'data-calcul' => 'diff', 'data-val-defaut' => sprintFloat($form->getObject()->total_droits_regulation))) ?>
			</p>

            <div class="col_btn">
                <button class="btn_valider" type="submit">Valider</button>
                <button class="btn_reinitialiser" type="submit">Annuler</button>
            </div>
        </div>
    </form>
</div>