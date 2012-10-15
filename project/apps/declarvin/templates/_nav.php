<?php if($with_etablissement): ?>
    <?php include_component('global', 'navTop', array('active' => $active)); ?>
<?php elseif($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
    <?php include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => $subactive)); ?>
<?php endif;