<?php include_component('global', 'navTop', array('active' => 'dsnegoce')); ?>
<section id="contenu">

	<h1>Déclaration de Stock</h1>
	
	<div class="col-xs-12">
        <div class="row">
        	<h4>Campagne <strong><?php echo $cm->getCampagneByDate(date('Y-m-d')) ?></strong> - Maisons de négoce</h4>
        	
        	<div class="col-xs-6">
        		<a href="/docs/dsnegoce-<?php echo strtolower($etablissement->getInterproObject()->identifiant) ?>.xls" class="btn btn-default pull-right">Télécharger la DS à compléter</a>
        	</div>
        	<div class="col-xs-6">
        		<a href="<?php echo url_for('dsnegoce_upload', $etablissement) ?>" class="btn btn-default"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;Importer la DS complétée</a>
        		
        	</div>
		</div>
		
		<div class="row">
        	<h4>Historique des déclarations de stock</h4>
        </div>
	</div>    

</section>