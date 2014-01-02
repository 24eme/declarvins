<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'produits')); ?>
<section id="contenu">
	<section id="principal"  class="produit">
	    <div id="mon_compte" class="clearfix">
	    
	    	<h1>Import du catalogue produit</h1>
	    	
	    	<?php if (count($erreurs) > 0): ?>
		    <div id="flash_message">
				<div class="flash_error">
					<table width="100%">
						<tr height="25px">
							<th width="150px" align="left">Ligne</th>
							<th align="left">Detail</th>
						</tr>
						<?php foreach ($erreurs as $k => $erreur): ?>
						<tr style="padding-bottom: 15px;">
							<td><?php echo $k ?></td>
							<td><?php echo $erreur ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		    <?php endif; ?>
		    
	    	<p>Merci de fournir le fichier de votre catalogue produit au format <strong><u>CSV</u></strong>.<br /><br />
	    	<form action="<?php echo url_for('configuration_produit_import') ?>" method="post" enctype="multipart/form-data">
			    <?php echo $form->renderHiddenFields(); ?>
			    <?php echo $form->renderGlobalErrors(); ?>			
			    <?php echo $form['file']->render() ?>
			    <?php echo $form['file']->renderError() ?>
				<div class="btn">
					<button class="btn_valider" type="submit">Uploader</button>
				</div>
			</form>
			
	    </div>
	</section>
</section>
