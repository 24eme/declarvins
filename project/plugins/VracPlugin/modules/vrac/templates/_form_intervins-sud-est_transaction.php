<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
	<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>
	<form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>

		<div>
			<div>
                <?php echo $form['export']->renderError() ?>
				<?php echo $form['export']->renderLabel() ?>
                <?php echo $form['export']->render() ?>
			</div>
			<div> 
				<?php foreach ($form['lots'] as $formLot): ?>
				<table>
					<tr>
						<td>
							<?php echo $formLot['numero']->renderError() ?>
							<?php echo $formLot['numero']->renderLabel() ?>
							<?php echo $formLot['numero']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['contenance_cuve']->renderError() ?>
							<?php echo $formLot['contenance_cuve']->renderLabel() ?>
							<?php echo $formLot['contenance_cuve']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['millesime']->renderError() ?>
							<?php echo $formLot['millesime']->renderLabel() ?>
							<?php echo $formLot['millesime']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['pourcentage_annee']->renderError() ?>
							<?php echo $formLot['pourcentage_annee']->renderLabel() ?>
							<?php echo $formLot['pourcentage_annee']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['degre']->renderError() ?>
							<?php echo $formLot['degre']->renderLabel() ?>
							<?php echo $formLot['degre']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['presence_allergenes']->renderError() ?>
							<?php echo $formLot['presence_allergenes']->renderLabel() ?>
							<?php echo $formLot['presence_allergenes']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['volume']->renderError() ?>
							<?php echo $formLot['volume']->renderLabel() ?>
							<?php echo $formLot['volume']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['date_retiraison']->renderError() ?>
							<?php echo $formLot['date_retiraison']->renderLabel() ?>
							<?php echo $formLot['date_retiraison']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $formLot['commentaires']->renderError() ?>
							<?php echo $formLot['commentaires']->renderLabel() ?>
							<?php echo $formLot['commentaires']->render() ?>
						</td>
					</tr>
				</table>
				<?php endforeach; ?>
			</div>
			<div>
                <?php echo $form['commentaires']->renderError() ?>
				<?php echo $form['commentaires']->renderLabel() ?>
				<?php echo $form['commentaires']->render() ?>
			</div>
		</div>
		<div class="ligne_form_btn">
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>