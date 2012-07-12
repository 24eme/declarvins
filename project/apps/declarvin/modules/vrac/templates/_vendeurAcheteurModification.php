<?php
use_helper('Display');
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();

$type = $form->getObject()->getFamilleType();
?>
<script type="text/javascript">
    $(document).ready(function() {
        init_ajax_modification('<?php echo $type;?>');
    });                        
</script>

<div class="vendeur_infos modification_infos bloc_form">
    
    <div class="col">
        <div class="vracs_ligne_form">
            <span><label><?php echo ($type=="vendeur")? 'Nom du vendeur ' : "Nom de l'acheteur "; ?> :</label>
            <?php echo $form->getObject()->nom; ?>    </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span><label>N° CVI </label>
                <?php echo $form->getObject()->cvi; ?>    </span>
        </div>
        <div class="vracs_ligne_form">
            <span><?php echo $form['no_accises']->renderLabel() ?>
            <?php echo $form['no_accises']->renderError(); ?>
            <?php echo $form['no_accises']->render() ?> </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt " >
            <span><?php echo $form['no_tva_intracommunautaire']->renderLabel() ?>
            <?php echo $form['no_tva_intracommunautaire']->renderError(); ?>
            <?php echo $form['no_tva_intracommunautaire']->render() ?> </span>
        </div>
    </div>
    
    <div class="col">
        <div class="vracs_ligne_form">
            <span><?php echo $form['adresse']->renderLabel() ?>
            <?php echo $form['adresse']->renderError(); ?>
            <?php echo $form['adresse']->render() ?>   </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span><?php echo $form['code_postal']->renderLabel() ?>
            <?php echo $form['code_postal']->renderError(); ?>
            <?php echo $form['code_postal']->render() ?></span>  
        </div>
        <div class="vracs_ligne_form">
            <span><?php echo $form['commune']->renderLabel() ?>
            <?php echo $form['commune']->renderError(); ?>
            <?php echo $form['commune']->render() ?> </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span><br /></span>
        </div>
    </div>
<div>