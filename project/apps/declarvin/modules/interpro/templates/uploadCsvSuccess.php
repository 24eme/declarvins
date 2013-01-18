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
    <p>Merci de fournir le fichier de vos établissements au format <strong><u>CSV</u></strong>.</p>
    <br />
    <?php if ($hasErrors): ?>
    <div id="flash_message">
		<div class="flash_error">
			<table width="100%">
				<tr height="25px">
					<th width="150px" align="left">Ligne</th>
					<th align="left">Detail</th>
				</tr>
				<?php foreach ($import->getErrors() as $k => $v): ?>
				<tr style="padding-bottom: 15px;">
					<td><?php echo $k ?></td>
					<td>
						<ul>
						<?php foreach ($v as $message): ?>
							<li><?php echo $message ?></li>
						<?php endforeach; ?>
						</ul>
						<br /><br />
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
    <?php endif; ?>
    <br />
    <?php include_partial('interpro/formUploadCsv', array('form' => $formUploadCsv, 'interpro' => $interpro, 'url' => url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))))) ?>
</div>
</section>