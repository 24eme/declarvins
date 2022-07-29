<?php include_component('global', 'nav', array('active' => 'vrac', 'subactive' => 'vrac')); ?>

<section id="contenu" class="vracs vrac_<?php echo $etape ?>">
    <?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape, 'vracEtape' => ($form->getObject()->etape)? $form->getObject()->etape : $etape, 'etablissement' => $etablissement)); ?>
    <?php if($configurationVrac->isContratPluriannuelActif() && $form->getObject()->isPluriannuelAdosse()): ?>
        <div class="titre" style="background-color: #eee; margin-top: 10px;padding: 5px 0;text-align:center;">
            <strong style="font-size: 13px; margin-right: 50px;background: url('/images/pictos/pi_pluriannuel.png') left 0 no-repeat;padding: 0px 5px 0 20px;">
                Contrat adossé au contrat pluriannuel n°<a target="_blank" href="<?php echo url_for('vrac_visualisation', array('contrat' => $form->getObject()->reference_contrat_pluriannuel)) ?>"><?php echo $form->getObject()->reference_contrat_pluriannuel ?></a>
            </strong>
        </div>
    <?php endif; ?>
    <?php include_partial('form_'.$etape, array('form' => $form, 'etape' => $etape, 'configurationVrac' => $configurationVrac, 'etablissement' => $etablissement, 'pluriannuel' => $pluriannuel)) ?>
</section>
