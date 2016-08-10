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
    var btn_modifier = presentation_infos.find('.btn_modifier');
    var btn_annuler = modification_infos.find('.btn_annuler');

    // modification_infos.hide();

    btn_modifier.click(function()
    {
        presentation_infos.hide();
        modification_infos.show();
        bloc.addClass('edition');
        return false;
    });

    btn_annuler.click(function()
    {
        presentation_infos.show();
        modification_infos.hide();
        bloc.removeClass('edition');
        return false;
    });

};
</script>
<!-- #principal -->
<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'contrat')); ?>
<section id="contenu">
<!-- #application_dr -->
<script type="text/javascript">
    var interproLocked = new Array();
<?php foreach ($compte->getNbEtablissementByInterproId() as $id => $nb): ?>
        interproLocked.push("<?php echo $id ?>");
<?php endforeach; ?>
</script>



<div class="clearfix" id="application_dr">

    <div id="contrat_mandat" >

        <h1 style="margin: 0 0 15px;">Contrat d'inscription</h1>
       
        <a href="<?php echo url_for("validation_pdf", array("num_contrat" => $contrat->no_contrat)) ?>" class="btn_suiv"><span>Télécharger le pdf du contrat d'inscription n°<?php echo $contrat->no_contrat ?></span></a>
        
        <?php if ($convention = $compte->getConventionCiel()): ?>
        <?php if($convention->valide): ?>
        <h1 style="margin: 15px 0 15px;">Convention CIEL</h1>
        <a href="<?php echo url_for("validation_convention", array("num_convention" => $convention->no_convention)) ?>" class="btn_suiv"><span>Télécharger la convention</span></a>
        <?php endif; ?>
        <?php endif; ?>
        
        <h1>Informations du compte</h1>
        
        <!-- #exploitation_administratif -->
        <div id="mon_compte">
        <?php include_partial('validation/formCompte', array('form' => $formCompte, 'compte' => $compte, 'contrat' => $contrat)) ?>
        </div>

        <h1>Etablissements associés</h1>
        
        <?php if (count($etablissements) > 0): ?>
        <ul class="chais">
            <?php foreach ($etablissements as $etablissement): ?>
            <li class="presentation" style="<?php if ($etablissement->statut == Etablissement::STATUT_ARCHIVE): ?>opacity:0.5;<?php endif; ?>">
                <?php include_partial('viewEtablissement', array('etablissement' => $etablissement, 'interpro' => $interpro, 'contrat' => $contrat, 'compte' => $compte)) ?>
            </li>
            <?php endforeach; ?>
        </ul>

        <?php else: ?>
        <p><i>Aucun établissement importé à ce jour</i></p>
        <?php endif; ?>
		<!-- 
        <!-- <h1>Liaison interpro</h1> -->
        <?php //include_partial('validation/formLiaisonInterpro', array('form' => $formLiaison, 'contrat' => $contrat)) ?>

        <h1>Validation</h1>
        <?php include_partial('validation/validation', array('valide_interpro' => $valide_interpro, 'compte_active' => $compte_active, 'interpro' => $interpro, 'contrat' => $contrat, 'nbEtablissement' => count($etablissements))) ?>

    </div>

</div>
<!-- fin #exploitation_administratif -->

<!-- fin #application_dr -->
</section>
<!-- fin #principal -->