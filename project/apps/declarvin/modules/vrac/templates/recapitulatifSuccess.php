<?php
/* Fichier : recapitulatifSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/recapitulatif
 * Affichage des dernières information de la saisie : numero de contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
?>
<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<form id="vrac_recapitulatif" method="get" action="<?php echo url_for('vrac_nouveau') ?>" class="popup_form">
		<h1>La saisie est terminée !</h1>
		<h2>N° d'enregistrement du contrat : <span><?php echo $vrac['numero_contrat']; ?></span></h2>
		<br /><br />
		<?php include_partial('showContrat', array('vrac' => $vrac)); ?>
		<a href="<?php echo url_for("vrac") ?>">Retour</a>
		<div class="ligne_form_btn">
			<button class="btn_valider" type="submit">Saisir un nouveau contrat</button>
		</div>
	</form>
</section>