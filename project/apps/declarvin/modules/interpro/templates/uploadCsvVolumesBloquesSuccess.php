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
<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'volumes')); ?>
<section id="contenu">


<div class="clearfix" id="mon_compte">
    <h1>Import des volumes bloqués pour les établissements</h1>
    <p>Merci de fournir le fichier de vos volumes bloqués pour chaque établissement au format <strong><u>CSV</u></strong>.</p>
    <br />
    <?php if (@file_get_contents($interpro->getAttachmentUri('volumes-bloques.csv'))): ?>
    <p>
	    <i>Vous avez un fichier de chargé (<a href="<?php echo $interpro->getAttachmentUri('volumes-bloques.csv'); ?>">télécharger le fichier</a>)</i>
    </p> 
    <?php else: ?>
    <p>
	    <i>Vous n'avez pas encore chargé de fichier.</i>
    </p>
    <?php endif; ?>
    <br />
    <?php include_partial('interpro/formUploadCsv', array('form' => $formUploadCsv, 'interpro' => $interpro, 'url' => url_for('interpro_upload_csv_volumes_bloques'))) ?>
</div>
</section>