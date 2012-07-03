<?php
/* Fichier : conditionSuccess.php
 * Description : Fichier php correspondant à la vue de vrac/XXXXXXXXXXX/condition
 * Formulaire concernant les conditions du contrat
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>
 <?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu"> 
	<?php include_partial('headerVrac', array('vrac' => $form->getObject(),'actif' => 3)); ?>
	<form id="vrac_condition" method="post" action="<?php echo url_for('vrac_condition',$vrac) ?>" class="popup_form">  
    	<?php echo $form->renderHiddenFields() ?>
        <?php echo $form->renderGlobalErrors() ?>
        <section id="condition">
        	<section id="type_contrat">
            	<?php echo $form['type_contrat']->renderError() ?>        
                <h2><?php echo $form['type_contrat']->renderLabel() ?></h2>
                <?php echo $form['type_contrat']->render() ?>
            </section>
            <section id="prix_isVariable">
            	<?php echo $form['prix_variable']->renderError() ?>        
                <h2><?php echo $form['prix_variable']->renderLabel() ?></h2>  
                <?php echo $form['prix_variable']->render() ?>        
            </section>
            <section id="vrac_marche_prixVariable">
            	<?php include_partial('condition_prixVariable', array('form' => $form)); ?>
            </section>
            <br />
            <h2>Dates</h2>
            <br />
            <section id="date_signature">
            	<?php echo $form['date_signature']->renderError() ?>        
                <?php echo $form['date_signature']->renderLabel() ?>
                <?php echo $form['date_signature']->render() ?>        
            </section>
            <br />
            <section id="date_stats">
            	<?php echo $form['date_stats']->renderError() ?>        
                <?php echo $form['date_stats']->renderLabel() ?>
                <?php echo $form['date_stats']->render() ?>        
            </section>
            <br />
        </section>
		<div class="ligne_form_btn">
			<a href="<?php echo url_for('vrac_marche', $vrac); ?>" class="btn_annuler">Précédent</a>
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
    </form>          
</section>