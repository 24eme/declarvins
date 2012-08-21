<?php if($with_etablissement): ?>
    <?php include_component('global', 'navTop', array('active' => $active)); ?>
<?php elseif($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
    <?php include_component('global', 'navBack', array('active' => $active)); ?>
<?php endif;