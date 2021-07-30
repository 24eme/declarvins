<?php echo use_helper("Date"); ?>
<?php include_component('global', 'navTop', array('active' => 'dsnegoce')); ?>
<section id="contenu">

	<h1>Déclaration de Stock <strong><?php echo $cm->getCampagneByDate(sfConfig::get('app_dsnegoceupload_date')) ?></strong></h1>

	<div class="col-xs-12">
        <div class="row">
        	<h4>Formulaire à déposer avant le <?php echo sfConfig::get('app_dsnegoceupload_cloture_libelle') ?></h4>
        	<?php if ($isCloture): ?>
        	<p class="text-center text-danger"><em>Le téléservice est cloturé</em></p>
        	<?php else: ?>
        	<div class="col-xs-6">

        		<div class="dropdown pull-right">
				  <button class="btn btn-default dropdown-toggle link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    Télécharger la DS à compléter
				    <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  <li><a href="/docs/dsnegoce-ir.xls?<?= date('Ym') ?>">Modèle Inter-Rhône</a></li>
				  <li><a href="/docs/dsnegoce-civp.xls?<?= date('Ym') ?>">Modèle Provence</a></li>
				  </ul>
				</div>


        	</div>
        	<div class="col-xs-6">
        		<a href="<?php echo url_for('dsnegoceupload_upload', $etablissement) ?>" class="btn btn-default"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;Déposer la DS complétée</a>
        	</div>
        	<?php endif; ?>
		</div>
		<p>&nbsp;</p>
		<div class="row">
        	<h4>Historique des DS importées</h4>
        	<?php if(count($history) > 0): ?>
        			<div class="list-group">
					<?php foreach ($history as $document): ?>
					<?php if (!preg_match('/^'.DSNegoceUploadClient::TYPE_MODEL.'/', $document->id)) continue; ?>
					<div class="list-group-item col-xs-12">
						<span class="col-sm-2 col-xs-12">
							<?php echo (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $document->key[PieceAllView::KEYS_DATE_DEPOT]))? format_date($document->key[PieceAllView::KEYS_DATE_DEPOT], "dd/MM/yyyy", "fr_FR") : null; ?>
						</span>
						<span class="col-sm-8 col-xs-12">
							<a href="<?php echo url_for('get_piece', array('doc_id' => $document->id, 'piece_id' => $document->value[PieceAllView::VALUES_KEY])) ?>"><?php echo substr($document->id, -3) .' - '. $document->key[PieceAllView::KEYS_LIBELLE] ?></a>
						</span>
						<span class="col-sm-2 col-xs-12">
							<a class="pull-right" href="<?php echo url_for('get_piece', array('doc_id' => $document->id, 'piece_id' => $document->value[PieceAllView::VALUES_KEY])) ?>"><span class="glyphicon glyphicon-file"></span></a>
						</span>
					</div>
					<?php endforeach; ?>
					</div>
        	<?php else: ?>
        	<p class="text-center"><em>Aucune DS déposée à ce jour</em></p>
        	<?php endif; ?>
        </div>
	</div>

</section>
