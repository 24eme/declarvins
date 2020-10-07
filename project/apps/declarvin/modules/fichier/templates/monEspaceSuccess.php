<?php include_component('global', 'navTop', array('active' => 'documents')); ?>
<section id="contenu">

<div class="row">

    <div class="col-xs-12">
		<?php include_partial('fichier/history', array('etablissement' => $etablissement, 'history' => PieceAllView::getInstance()->getPiecesByEtablissement($etablissement->identifiant, $sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)), 'limit' => Piece::LIMIT_HISTORY)); ?>
    </div>
</div>

</section>
