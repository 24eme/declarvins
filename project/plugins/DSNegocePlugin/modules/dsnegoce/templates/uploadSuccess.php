<?php include_component('global', 'navTop', array('active' => 'dsnegoce')); ?>
<section id="contenu">

	<div class="col-xs-12">
        <div class="row">
        	<h4>Importer la DS complétée</h4>
        	
        	<div class="col-md-12" style="margin-top: 20px;">

				<form class="form-horizontal" role="form" action="<?php echo url_for("dsnegoce_upload", array('sf_subject' => $etablissement)) ?>" method="post" enctype="multipart/form-data">
				    <?php echo $form->renderHiddenFields(); ?>
				    <?php echo $form->renderGlobalErrors(); ?>
				    
				    <div class="panel panel-default">
			        	<div class="panel-heading"><h4 class="panel-title">Fichier</h4></div>
			        	<div class="panel-body">
				
				    	<div class="form-group <?php if($form['file']->hasError()): ?>has-error<?php endif; ?>">
							<?php echo $form['file']->renderError() ?>
					        <?php echo $form['file']->renderLabel(null, array("class" => "col-xs-1 control-label")); ?>
							<div class="col-xs-5">
								<?php echo $form['file']->render() ?>
							</div>
						</div>
						
						</div>
					</div>
					
					<div class="col-xs-6"><a href="<?php echo url_for('dsnegoce_mon_espace', $etablissement) ?>" class="btn btn-default">Annuler</a></div>
	    			<div class="col-xs-6 text-right"><button id="btn_valider" type="submit" class="btn btn-success">Importer</button></div>
				    
				</form>
			
			</div>

		</div>
	</div>

</section>
