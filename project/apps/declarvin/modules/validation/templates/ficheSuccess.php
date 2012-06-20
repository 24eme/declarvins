
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

    //modification_infos.hide();

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
<?php include_component('global', 'navBack', array('active' => 'contrat')); ?>
<section id="contenu">
<!-- #application_dr -->
<script type="text/javascript">
    var interproLocked = new Array();
<?php foreach ($compte->getNbEtablissementByInterproId() as $id => $nb): ?>
        interproLocked.push("<?php echo $id ?>");
<?php endforeach; ?>
</script>
<div class="clearfix" id="validationFiche">
    <h1>Informations du compte</h1>
    <!-- #exploitation_administratif -->
    <div id="mon_compte">
        <?php include_partial('validation/formCompte', array('form' => $formCompte, 'compte' => $compte, 'contrat' => $contrat)) ?>
    </div>
    
    <h1>Etablissements associés</h1>
    <?php if (count($etablissements) > 0 || count($etablissementsCsv) > 0): ?>
        <ul class="chais">
            <?php foreach ($etablissements as $etablissement): ?>
                <li class="presentation"<?php if ($etablissement->statut == _Tiers::STATUT_ARCHIVER): ?> style="opacity:0.5;"<?php endif; ?>>
                    <?php include_partial('viewEtablissement', array('etablissement' => $etablissement, 'interpro' => $interpro, 'contrat' => $contrat)) ?>
                </li>
            <?php endforeach; ?>
            <?php foreach ($etablissementsCsv as $etablissementCsv): ?>
                <li class="presentation" style="border:1px dashed #C7C9C8;">
                    <?php include_partial('viewCsvEtablissement', array('etablissement' => $etablissementCsv, 'interpro' => $interpro, 'contrat' => $contrat)) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><i>Aucun établissement importé à ce jour</i></p>
    <?php endif; ?>
    <br /><br />
    <h1>Liaison interpro</h1>
    <?php include_partial('validation/formLiaisonInterpro', array('form' => $formLiaison, 'contrat' => $contrat)) ?>
    
    <br /><br />
    <h1>Validation</h1>
    <?php include_partial('validation/validation', array('valide_interpro' => $valide_interpro, 'compte_active' => $compte_active, 'interpro' => $interpro, 'contrat' => $contrat)) ?>

</div>
<!-- fin #exploitation_administratif -->

<!-- fin #application_dr -->
</section>
<!-- fin #principal -->