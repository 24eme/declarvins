<!-- #principal -->

<!-- #application_dr -->

<?php if($sf_user->hasFlash('general')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('general'); ?></p>
<?php endif; ?>
<div class="clearfix" id="application_dr">
    <h2 class="titre_principal">Compte</h2>
    <!-- #exploitation_administratif -->
    <div id="mon_compte">
        <?php include_partial('compte/view_form', array('form' => $form, 'compte' => $compte))?>
    </div>
    <h2 class="titre_principal">Chais associés</h2>
    <?php if($sf_user->hasFlash('chai')) : ?>
        <p class="flash_message"><?php echo $sf_user->getFlash('chai'); ?></p>
    <?php endif; ?>
    <ul class="chais">
        <?php foreach ($chais as $chai): ?>
        <li class="presentation">
            <?php if ($chai->getIdentifiant() == $identifiant): ?>
                <?php include_partial('chai/form_update', array('form' => $formChai)) ?>
            <?php else: ?>
                <?php include_partial('chai/view', array('chai' => $chai, 'interpro' => $interpro)) ?>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
        <li class="presentation">
            <?php if ($action == 'new'): ?>
            <?php include_partial('chai/form', array('form' => $formChai)) ?>
           <?php else: ?>
            <a href="<?php echo url_for('@compte_new_chai') ?>" class="new">Ajouter un chai</a>
           <?php endif; ?>
        </li>
    </ul>
    <h2 class="titre_principal">Liaison interpro</h2>
    <?php include_partial('compte/form_liaison_interpro', array('form' => $formLiaison)) ?>
    <h2 class="titre_principal">Validation</h2>

</div>
<!-- fin #exploitation_administratif -->

<!-- fin #application_dr -->

<!-- fin #principal -->