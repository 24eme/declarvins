<?php include_partial('global/navTop', array('active' => 'drm')); ?>

<section id="contenu">

    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'informations', 'pourcentage' => '5')); ?>

    <!-- #principal -->
    <section id="principal">
		<div id="application_dr">
	        <p>Veuillez tout d'abord confirmer les informations ci-dessous :<br /><br /></p>
	        <table width="780px">
	        	<tr>
	        		<td width="500px" height="35px">CVI :</td>
	        		<td><?php echo $tiers->cvi ?></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">N° SIRET :</td>
	        		<td><?php echo $tiers->siret ?></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">N° entrepositaire agréé :</td>
	        		<td><?php echo $tiers->no_tva_intracommunautaire ?></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">Nom / Raison Sociale / Adresse :</td>
	        		<td><?php echo $tiers->nom ?>,<br /><?php echo $tiers->siege->adresse ?><br /><?php echo $tiers->siege->code_postal ?> <?php echo $tiers->siege->commune ?></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">Lieu ou est tenu la comptabilité matière :</td>
	        		<td><?php if (!$tiers->comptabilite->adresse): ?>IDEM<?php else: ?><?php echo $tiers->comptabilite->adresse ?><br /><?php echo $tiers->comptabilite->code_postal ?> <?php echo $tiers->comptabilite->commune ?><?php endif; ?></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">Service des douanes :</td>
	        		<td><?php echo $tiers->service_douane ?></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">Numéro d'Accise</td>
	        		<td>16879908</td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">Je confirme l'exactitude de ces informations :</td>
	        		<td><input type="radio" value="1" name="confirm" /></td>
	        	</tr>
	        	<tr>
	        		<td width="500px" height="35px">Je souhaite modifier mes informations de structure :</td>
	        		<td><input type="radio" value="0" name="confirm" /></td>
	        	</tr>
	        </table>
	    </div>
        <div id="btn_etape_dr">
            <a href="<?php echo url_for('drm_mouvements_generaux') ?>" class="btn_suiv">Valider</a>
        </div>
    </section>
</section>
