<style>

.btn_valider {
    background-color: #820608;
    background-position: right -52px;
    background-repeat: no-repeat;
    border: 1px solid #A12929;
    color: #FFFFFF;
    display: inline-block;
    padding: 0 23px 0 15px;
    text-transform: uppercase;
    height: 20px;
    margin: 10px 0 0 0;
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
<?php include_component('global', 'navBack', array('active' => 'contrat')); ?>
<section id="contenu">


<div class="clearfix" id="application_dr">
    <h1>Import des établissements</h1>
    <?php if ($sf_user->hasFlash('notification_general')) : ?>
    <p class="flash_message"><i><?php echo $sf_user->getFlash('notification_general'); ?></i></p><br /><br />
	<?php endif; ?>
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