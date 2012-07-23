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
				<table id="table_lots">
					<?php include_partial('form_lots_item', array('form' => $formLot)) ?>
				</table>
				<?php endforeach; ?>
                </table>
                <a class="btn_ajouter_ligne_template" data-container="#table_lots" data-template="#template_form_lots_item" href="#">Ajouter</a>
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

<?php /*include_partial('form_collection_template', array('partial' => 'form_lots_item', 
                                                        'form' => $form->getFormTemplateLots()));*/?>