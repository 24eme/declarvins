<?php
/* Fichier : _marche_volumePrix.php
 * Description : Fichier php correspondant à la vue partielle de vrac/XXXXXXXXXXX/marche
 * Partie du formulaire permettant le choix des volumes et des prix, affichage du total
 * Auteur : Petit Mathurin - mpetit[at]actualys.com
 * Version : 1.0.0 
 * Derniere date de modification : 28-05-12
 */
 ?>

<!--  Affichage des contenances disponibles (seulement s'il s'agit de vins en bouteilles)  -->
<div id="vrac_marche_volumePropose" class="section_label_maj">
    <label>Volumes proposés</label>
    <div class="bloc_form" >
        <div id="contenance" class="bouteilles_contenance vracs_ligne_form " >
            <span>
                <?php echo $form['bouteilles_contenance']->renderLabel() ?>
                <?php echo $form['bouteilles_contenance']->render() ?>
                <?php echo $form['bouteilles_contenance']->renderError() ?>
            </span>
        </div>

        <!--  Affichage des volumes disponibles variables selon le type de transaction choisi  -->
        <div id="volume" class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <div class="bouteilles_quantite">
                    <strong> <?php echo $form['bouteilles_quantite']->renderLabel() ?></strong>
                    <?php echo $form['bouteilles_quantite']->render() ?>
                    <span id="volume_unite_total" class="unite"></span>
                    <?php echo $form['bouteilles_quantite']->renderError() ?>
                </div>
                <div class="jus_quantite">
                    <strong>  <?php echo $form['jus_quantite']->renderLabel() ?></strong>
                    <?php echo $form['jus_quantite']->render() ?>
                    <span id="volume_unite_total" class="unite"></span>
                    <?php echo $form['jus_quantite']->renderError() ?>
                </div>
                <div class="raisin_quantite">
                    <strong>  <?php echo $form['raisin_quantite']->renderLabel() ?></strong>
                    <?php echo $form['raisin_quantite']->render() ?>
                    <span id="volume_unite_total" class="unite"></span>
                    <?php echo $form['raisin_quantite']->renderError() ?>
                </div>  
            </span>
        </div>
    </div>
    
    <!--  Affichage du prix unitaire variables selon le type de transaction choisi -->
    <div id="prixUnitaire" class="section_label_maj">
        <?php echo $form['prix_unitaire']->renderLabel() ?>
        <?php echo $form['prix_unitaire']->render() ?>        
        <span id="prix_unitaire_unite" class="unite"></span>
        <span id="prix_unitaire_hl" class="small"></span>
        <?php echo $form['prix_unitaire']->renderError(); ?>
    </div>
    
    <!--  Affichage du prix total (quantité x nbproduit)  -->
    <div class="bloc_form" >
        <div id="prixTotal" class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <label id="vrac_prix_total_label">Prix total</label>
                <span id="vrac_prix_total" class="unite"></span>
                <span id="prix_unite" class="small">€</span> 
            </span>
        </div>
    </div>
</div>
            
                