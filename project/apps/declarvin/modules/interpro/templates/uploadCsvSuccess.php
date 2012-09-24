<style>

#mon_compte .btn_valider {
    margin-left: 0px;
}
.error_list {    
	display: inline-block;
	width: 200px;
    font-size: 100%;
    font-weight: normal;
    height: 16px;
    padding: 1px;
    margin: 0;
	
}
.flash_message {
	background-color: #B1CB00;
    background-position: 10px center;
    background-repeat: no-repeat;
    border: 1px solid #99AF00;
    color: #FFFFFF;
    display: inline-block;
    font-size: 15px;
    line-height: 1;    
    padding: 4px 20px;
    text-align: center;
    text-transform: uppercase;
}
</style>
<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'contrat')); ?>
<section id="contenu">


<div class="clearfix" id="mon_compte">
    <h1>Import des établissements</h1>
    <?php if (@file_get_contents($interpro->getAttachmentUri('etablissements.csv'))): ?>
        <p>
            <i>Fichier prêt pour l'import (<a href="<?php echo $interpro->getAttachmentUri('etablissements.csv'); ?>">télécharger le fichier</a>)</i><br />
            <a class="btn_valider" href="<?php echo url_for("interpro_import", array('id' => $interpro->get('_id'))) ?>">Lancer l'update</a>
        </p> 
        <br />
    <?php else: ?>
        <p>
        <i>Vous n'avez pas encore chargé de fichier d'import.</i>
        </p>
        <br />
    <?php endif; ?>
    <?php include_partial('interpro/formUploadCsv', array('form' => $formUploadCsv, 'interpro' => $interpro)) ?>
</div>
</section>