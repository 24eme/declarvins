<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<section id="contenu" class="vracs vrac_<?php echo $etape ?>">
    <?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape, 'pluriannuel' => $pluriannuel, 'referenceContratPluriannuel' => $referenceContratPluriannuel, 'vracEtape' => ($form->getObject()->etape)? $form->getObject()->etape : $etape, 'etablissement' => $etablissement)); ?>
    <?php include_partial('form_'.$etape, array('form' => $form, 'etape' => $etape, 'configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'pluriannuel' => $pluriannuel, 'pacte_cooperatif' => $pacte_cooperatif)) ?>
</section>
