<?php include_partial('global/navTop'); ?>

<section id="contenu">

    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'informations', 'pourcentage' => '5')); ?>

    <!-- #principal -->
    <section id="principal">
		<div id="application_dr">
	        <p>Veuillez tout d'abord confirmer les informations ci-dessous :</p>
	        <ul style="width: 780px; float:left; margin: 20px 30px 30px 50px;">
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>CVI :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->cvi ?>&nbsp;</li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>N° SIRET :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->siret ?>&nbsp;</li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>N° entrepositaire agréé :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->no_tva_intracommunautaire ?>&nbsp;</li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>Nom / Raison Sociale / Adresse :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->nom ?>,<br /><?php echo $tiers->siege->adresse ?><br /><?php echo $tiers->siege->code_postal ?> <?php echo $tiers->siege->commune ?>&nbsp;</li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>Lieu ou est tenu la comptabilité matière :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><?php if (!$tiers->comptabilite->adresse): ?>IDEM<?php else: ?><?php echo $tiers->comptabilite->adresse ?><br /><?php echo $tiers->comptabilite->code_postal ?> <?php echo $tiers->comptabilite->commune ?><?php endif; ?>&nbsp;</li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>Service des douanes :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><?php echo $tiers->service_douane ?>&nbsp;</li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>Numéro d'Accise</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;">16879908</li>
	            <li style="width: 50%; float:left; margin: 40px 0 5px 0;"><b>Je confirme l'exactitude de ces informations :</b></li>
	            <li style="width: 50%; float:left;margin: 40px 0 5px 0;"><input type="radio" value="1" name="confirm" /></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><b>Je souhaite modifier mes informations de structure :</b></li>
	            <li style="width: 50%; float:left; margin: 5px 0;"><input type="radio" value="0" name="confirm" /></li>
	        </ul>
	
	        <div id="btn_etape_dr">
	            <a href="<?php echo url_for('drm_mouvements_generaux') ?>" class="btn_suiv">Valider</a>
	        </div>
	    </div>
    </section>
</section>
