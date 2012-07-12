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
<section id="contenu" class="vracs">
    <?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 2)); ?>
	<form id="vrac_marche" method="post" action="<?php echo url_for('vrac_marche',$vrac) ?>" class="popup_form" >
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<div id="marche">
                    <div id="original" class="original"> 
                            <?php echo $form['original']->renderLabel() ?> 
                            <?php echo $form['original']->render() ?>
                            <?php echo $form['original']->renderError(); ?>
                    </div>
                    <div id="type_transaction" class="type_transaction section_label_maj">
                            <?php echo $form['type_transaction']->renderLabel() ?>
                            <?php echo $form['type_transaction']->render() ?>
                            <?php echo $form['type_transaction']->renderError(); ?> 
                    </div>
                    <div id="vrac_marche_produitLabel"> 
                            <?php include_partial('marche_produitLabel', array('form' => $form)); ?> 
                    </div>
                    <div id="vrac_marche_volumePrix">
                            <?php include_partial('marche_volumePrix', array('form' => $form)); ?>
                    </div>
		</div>	
		<div class="ligne_form_btn">
			<a href="<?php echo url_for('vrac_soussigne', $vrac); ?>" class="btn_annuler">Précédent</a>
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>
<script type="text/javascript">
	$(document).ready(function()  {
    	$('.autocomplete').combobox();
	});
</script>
