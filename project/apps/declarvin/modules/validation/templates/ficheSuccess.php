<style>
#modification_compte span,
#modification_compte label {
	display: inline-block;
	width: 200px;
	padding: 5px 0;
}
#modification_compte input[type="text"],
#modification_compte input[type="password"],
#modification_compte select {
	background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #D3D2CD;
    border-radius: 2px 2px 2px 2px;
    font-size: 100%;
    position: relative;
    vertical-align: middle;
    height: 18px;
    padding: 0 4px;
    width: 220px;
}
#liaisonInterpro {
	margin: 0 0 10px 0;
}
#formLiaisonInterpro .btn span {
	display: inline-block;
	width: 21px;
}
#formLiaisonInterpro {
	margin: 0 0 10px 0;
}
.btn .btn_valider {
    background-color: #820608;
    background-position: right -52px;
    background-repeat: no-repeat;
    border: 1px solid #A12929;
    color: #FFFFFF;
    display: inline-block;
    padding: 0 23px 0 15px;
    text-transform: uppercase;
    height: 20px;
}
#modification_compte {
	height: 215px;
	margin: 0 0 10px 0;
}
#modification_compte .error_list {    
	display: inline-block;
	width: 200px;
    font-size: 100%;
    font-weight: normal;
    height: 16px;
    padding: 1px;
    margin: 0;
	
}
.chais li {
	width: 275px;
	padding: 10px;
	display: inline-block;

}
</style>
<script type="text/javascript">
$(document).ready( function()
{
    if ($('#modification_compte').length > 0) {
        $('#modification_compte').ready( function() {
            formModificationCompte();
        });
    }
});
var formModificationCompte = function()
{
    var bloc = $('#modification_compte');

    var presentation_infos = bloc.find('.presentation');
    var modification_infos = bloc.find('.modification');
    var btn = bloc.find('.btn');
    var btn_modifier = btn.find('a.modifier');
    var btn_annuler = btn.find('a.annuler');

    // modification_infos.hide();

    btn_modifier.click(function()
    {
        presentation_infos.hide();
        modification_infos.show();
        $("a.modifier").hide();
        bloc.addClass('edition');
        return false;
    });

    btn_annuler.click(function()
    {
        presentation_infos.show();
        modification_infos.hide();
        $("a.modifier").show();
        bloc.removeClass('edition');
        return false;
    });

};
</script>
<!-- #principal -->
<?php include_partial('global/navBack', array('active' => 'contrat')); ?>
<section id="contenu">
<!-- #application_dr -->
<div class="tools" style="text-align: right;">
    <span>Vous êtes loggué en tant que <strong><?php echo $interpro; ?></strong></span>&nbsp; | &nbsp;<span><a href="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>">Gestion CSV</a></span>&nbsp; | &nbsp;<span><a href="<?php echo url_for('@validation_login') ?>">Déconnexion</a></span>
</div>
<script type="text/javascript">
    var interproLocked = new Array();
<?php foreach ($compte->getNbEtablissementByInterproId() as $id => $nb): ?>
        interproLocked.push("<?php echo $id ?>");
<?php endforeach; ?>
</script>
<?php if ($sf_user->hasFlash('notification_general')) : ?>
    <p class="flash_message"><?php echo $sf_user->getFlash('notification_general'); ?></p>
<?php endif; ?>
<div class="clearfix" id="application_dr">
    <h1>Informations du compte</h1>
    <!-- #exploitation_administratif -->
    <div id="mon_compte">
        <?php include_partial('validation/formCompte', array('form' => $formCompte, 'compte' => $compte)) ?>
    </div>
    
    <h1>Etablissements associés</h1>
    <?php if (count($etablissements) > 0 || count($etablissementsCsv) > 0): ?>
        <ul class="chais">
            <?php foreach ($etablissements as $etablissement): ?>
                <li class="presentation"<?php if ($etablissement->statut == _Tiers::STATUT_ARCHIVER): ?> style="opacity:0.5;"<?php endif; ?>>
                    <?php include_partial('viewEtablissement', array('etablissement' => $etablissement, 'interpro' => $interpro)) ?>
                </li>
            <?php endforeach; ?>
            <?php foreach ($etablissementsCsv as $etablissementCsv): ?>
                <li class="presentation" style="border:1px dashed #C7C9C8;">
                    <?php include_partial('viewCsvEtablissement', array('etablissement' => $etablissementCsv, 'interpro' => $interpro)) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><i>Aucun établissement importé à ce jour</i></p>
    <?php endif; ?>

    <h1>Liaison interpro</h1>
    <?php include_partial('validation/formLiaisonInterpro', array('form' => $formLiaison)) ?>
    <h1>Validation</h1>
    <?php include_partial('validation/validation', array('valide_interpro' => $valide_interpro, 'compte_active' => $compte_active, 'interpro' => $interpro)) ?>

</div>
<!-- fin #exploitation_administratif -->

<!-- fin #application_dr -->
</section>
<!-- fin #principal -->