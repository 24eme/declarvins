<?php
/* Fichier : marcheSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/marche
 * Formulaire d'enregistrement de la partie marche d'un contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : ${date}
 */
?>
<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu"> <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 2)); ?>
	<form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche',$vrac) ?>" class="popup_form" >
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<section id="marche">
			<section id="original" class="original"> <?php echo $form['original']->renderError(); ?>
				<strong> <?php echo $form['original']->renderLabel() ?> </strong> <?php echo $form['original']->render() ?>
			</section>
			<section id="type_transaction" class="type_transaction">
				<h2><?php echo $form['type_transaction']->renderLabel() ?></h2>
				<?php echo $form['type_transaction']->renderError(); ?> <?php echo $form['type_transaction']->render() ?>
			</section>
			<section id="vrac_marche_produitLabel"> 
				<?php include_partial('marche_produitLabel', array('form' => $form)); ?> 
			</section>
			<section id="vrac_marche_volumePrix">
				<?php include_partial('marche_volumePrix', array('form' => $form)); ?>
			</section>
		</section>	
		<div class="ligne_form_btn">
			<a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_annuler">Précédent</a>
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>

