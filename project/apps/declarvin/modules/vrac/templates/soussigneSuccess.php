<?php
/* Fichier : soussigneSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/nouveau-soussigne
 * Formulaire d'enregistrement de la partie soussigne des contrats (modification de contrat)
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 29-05-12
 */
$nouveau = is_null($form->getObject()->numero_contrat);
$hasmandataire = !is_null($form->getObject()->mandataire_identifiant);

if($nouveau)
{
?>
<script type="text/javascript">
    $(document).ready(function() 
    {
         ajaxifyAutocompleteGet('getInfos',{autocomplete : '#vendeur_choice'},'#vendeur_informations');
		 ajaxifyAutocompleteGet('getInfos',{autocomplete : '#acheteur_choice'},'#acheteur_informations'); 
	     ajaxifyAutocompleteGet('getInfos',{autocomplete : '#mandataire_choice'},'#mandataire_informations');
		 $('section#has_mandataire input').attr('checked', 'checked');    
		 $('#vrac_mandatant_vendeur').attr('checked','checked');
		    
		 majAutocompleteInteractions('vendeur');
		 majAutocompleteInteractions('acheteur');
		 majAutocompleteInteractions('mandataire');
    majMandatairePanel();
    });                        
</script>
<?php
}
else 
{
  $numero_contrat = $form->getObject()->numero_contrat;
?>
<script type="text/javascript">
    $(document).ready(function() 
    {
        ajaxifyAutocompleteGet('getInfos',{autocomplete : '#vendeur_choice','numero_contrat' : '<?php echo $numero_contrat;?>'},'#vendeur_informations');        
        ajaxifyAutocompleteGet('getInfos',{autocomplete : '#acheteur_choice','numero_contrat' : '<?php echo $numero_contrat;?>'},'#acheteur_informations');
        ajaxifyAutocompleteGet('getInfos',{autocomplete : '#mandataire_choice','numero_contrat' : '<?php echo $numero_contrat;?>'},'#mandataire_informations');
        majMandatairePanel();
    });
</script>
<?php
}
?>
<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 1)); ?>
	<form id="vrac_soussigne" class="popup_form" method="post"
		action="<?php echo ($form->getObject()->isNew())? url_for('vrac_nouveau') : url_for('vrac_soussigne',$vrac); ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
	
		<section id="vendeur">
			<?php echo $form['vendeur_identifiant']->renderError(); ?>
			<?php echo $form['vendeur_famille']->render(array('class' => 'famille')) ?>
			<section id="vendeur_choice">
				<h1>
					<strong><?php echo $form['vendeur_identifiant']->renderLabel() ?></strong>
					<?php echo $form['vendeur_identifiant']->render() ?>
				</h1>
			</section>
			<section id="vendeur_informations"> 
			<?php   
				$vendeurArray = array();
				$vendeurArray['vendeur'] = $form->vendeur;
				$vendeurArray['vendeur'] = ($nouveau)? $vendeurArray['vendeur'] : $form->getObject()->getVendeurObject();
				include_partial('vendeurInformations', $vendeurArray);
			?>
			</section>
			<div class="ligne_form_btn">
				<a id="vendeur_modification_btn" class="btn_valider" style="cursor: pointer;">Modifier</a>
			</div>
		</section>
	

		<section id="acheteur">
			<section id="acheteur_choice">
				<?php echo $form['acheteur_identifiant']->renderError(); ?>
				<?php echo $form['acheteur_famille']->render(array('class' => 'famille')) ?>
				<h1>
					<strong><?php echo $form['acheteur_identifiant']->renderLabel() ?></strong>
					<?php echo $form['acheteur_identifiant']->render() ?>
				</h1>
			</section> <!--  Affichage des informations sur l'acheteur sélectionné AJAXIFIED -->
			<section id="acheteur_informations">
			<?php
				$acheteurArray = array();
				$acheteurArray['acheteur'] = $form->acheteur;
				$acheteurArray['acheteur'] = ($nouveau)? $acheteurArray['acheteur'] : $form->getObject()->getAcheteurObject();
				include_partial('acheteurInformations', $acheteurArray);
			?>
			</section>
			<div class="ligne_form_btn">
				<a id="acheteur_modification_btn" class="btn_valider" style="cursor: pointer;">Modifier</a>
			</div>
		</section>
	
	
		<section id="has_mandataire" style="margin-bottom: 15px;">
			<?php echo $form['mandataire_exist']->render() ?>
			<strong><?php echo $form['mandataire_exist']->renderLabel() ?></strong>
			<?php echo $form['mandataire_exist']->renderError(); ?>
		</section>
		<section id="mandataire">
			<section id="mandatant" style="margin-bottom: 15px;"> 
				<?php echo $form['mandatant']->renderError(); ?>
				<strong><?php echo $form['mandatant']->renderLabel() ?></strong> 
				<?php echo $form['mandatant']->render() ?>
			</section>
			<section id="mandataire_choice">
				<?php echo $form['mandataire_identifiant']->renderError(); ?>
				<h1>
					<strong><?php echo $form['mandataire_identifiant']->renderLabel() ?></strong>
					<?php echo $form['mandataire_identifiant']->render() ?>
				</h1>
	
			</section>
			<section id="mandataire_informations">
			<?php
				$mandataireArray = array();
				$mandataireArray['mandataire'] = $form->mandataire;
				if(!$nouveau)
					$mandataireArray['mandataire'] = (!$hasmandataire)? $mandataireArray['mandataire'] : $form->getObject()->getMandataireObject();
				include_partial('mandataireInformations', $mandataireArray);
			?> 
			</section>
			<div class="ligne_form_btn">
				<a id="mandataire_modification_btn" class="btn_valider" style="cursor: pointer;">Modifier</a>
			</div>
		</section>
	
		<div class="ligne_form_btn">
			<?php if($nouveau): ?>
			<a href="<?php echo url_for('vrac'); ?>" class="btn_annuler">Annuler la saisie</a>
			<?php endif; ?>
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>
<script type="text/javascript">
	$(document).ready(function()  {
    	$('.autocomplete').combobox();
    	$('input.famille"').change(function() {
    		select = $('#'+$(this).attr('data-autocomplete'));		
    		select.attr('data-ajax', select.attr('data-ajax-structure').replace('%25familles%25', $(this).val()));
    		//select.parent().find('.ui-autocomplete-input').val("");
    		//select.html("<option value=''></option>");
    	});
	});
</script>