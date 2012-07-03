<?php
/* Fichier : validationSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/validation
 * Formulaire d'enregistrement de la partie validation d'un contrat donnant le récapitulatif
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
?>
<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu"> <?php include_partial('headerVrac', array('vrac' => $vrac,'actif' => 4)); ?> 
 	<form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation',$vrac) ?>" class="popup_form">
		<h1>Récapitulatif de la saisie</h1>
		<?php include_partial('showContrat', array('vrac' => $vrac)); ?>
		<div class="ligne_form_btn">
			<button class="btn_valider" type="submit">Valider le contrat</button>
		</div>
	</form>
</section>