<?php
use_helper('Display');
echo $form->renderHiddenFields();
echo $form->renderGlobalErrors();
?>
<script type="text/javascript">
    $(document).ready(function() {
        init_ajax_modification('<?php echo $type;?>');
    });                        
</script>
<table class="vendeur_infos">
        <tr>
            <td class="bold">Nom du <?php echo $type; ?>:&nbsp;</td>
            <td><?php echo $form->getObject()->nom; ?></td>
        </tr>
        <tr>
            <td class="bold"><?php echo $form['adresse']->renderLabel() ?>:&nbsp;</td>
            <td>
                <?php echo $form['adresse']->renderError(); ?>
                <?php echo $form['adresse']->render() ?>      
            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo $form['cvi']->renderLabel() ?>:&nbsp;</td>
            <td>
               <?php echo $form['cvi']->renderError(); ?>
               <?php echo $form['cvi']->render() ?>      
            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo $form['code_postal']->renderLabel() ?>:&nbsp;</td>
            <td>
                <?php echo $form['code_postal']->renderError(); ?>
                <?php echo $form['code_postal']->render() ?>   
            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo $form['no_accises']->renderLabel() ?>:&nbsp;</td>
            <td>
                <?php echo $form['no_accises']->renderError(); ?>
                <?php echo $form['no_accises']->render() ?> 
            </td>
        </tr>
        <tr>    
            <td class="bold"><?php echo $form['commune']->renderLabel() ?>:&nbsp;</td>
            <td>
              <?php echo $form['commune']->renderError(); ?>
              <?php echo $form['commune']->render() ?> 
            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo $form['no_tva_intracommunautaire']->renderLabel() ?>:&nbsp;</td>
            <td>
                <?php echo $form['no_tva_intracommunautaire']->renderError(); ?>
                <?php echo $form['no_tva_intracommunautaire']->render() ?> 
            </td>
        </tr>
</table>