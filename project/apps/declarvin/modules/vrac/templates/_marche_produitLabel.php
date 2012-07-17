<?php
/* Fichier : _marche_produitLabel.php
 * Description : Fichier php correspondant à la vue partielle de vrac/XXXXXXXXXXX/marche
 * Partie du formulaire permettant le choix du produit et du label
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */


 $has_domaine = ! is_null($form->getObject()->domaine);
 ?>


<!--  Affichage des produits disponibles (en fonction de la transaction choisie  -->
<div id="produit" class="section_label_maj">
    <?php echo $form['produit']->renderLabel() ?>
    <?php echo $form['produit']->render() ?>
    <?php echo $form['produit']->renderError(); ?>
</div>

<!--  Affichage des millésimes  -->
<div id="millesime" class="section_label_maj">
    <?php echo $form['millesime']->renderLabel() ?> 
    <?php echo $form['millesime']->render() ?>
    <?php echo $form['millesime']->renderError(); ?>
</div>

<!--  Affichage du type  -->
<div id="type" class="section_label_maj">
    <label> Type </label>
    
    <input type="radio" value="generique" name="type_produit" <?php echo ($has_domaine)? '' : 'checked="checked"'; ?> />
    <label for="generique">Générique</label>   

    <input type="radio" value="domaine" name="type_produit" <?php echo ($has_domaine)? 'checked="checked"' : ''; ?> />
    <label for="domaine">Domaine</label>
</div>


<!--  Affichage du type  -->
 <div id="domaine" class="bloc_form">
    <div class="vracs_ligne_form vracs_ligne_form_alt">
        <?php echo $form['domaine']->renderLabel() ?> 
        <?php echo $form['domaine']->render() ?>
        <?php echo $form['domaine']->renderError(); ?>
    </div>
</div>


<!--  Affichage des label disponibles -->
<div id="label" class="section_label_maj">
    <?php echo $form['label']->renderLabel() ?> 
    <?php echo $form['label']->render() ?>
    <?php echo $form['label']->renderError(); ?>
</div>
<!--  
<br>

<section id="stock">
    <strong>Stocks disponibles</strong> 
        
        <?php 
       // echo "500 hl";
        ?>
</section>
        
Affichage du stock disponible pour ce produit WARNING TO AJAXIFY -->