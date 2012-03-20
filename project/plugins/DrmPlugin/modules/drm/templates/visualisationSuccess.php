<?php use_helper('Float'); ?>
<?php include_partial('global/navTop', array('active' => 'drm')); ?>


<section id="contenu">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>

    <!-- #principal -->
    <section id="principal">

        <div id="contenu_onglet">
            <?php include_partial('drm/recap', array('drm' => $drm)) ?>
        
    <?php foreach ($drm->getDroits() as $typedroit => $droits) : ?>
    <div class="tableau_ajouts_liquidations">
    <h2>Droits <?php echo $typedroit; ?></h2>
    	<table class="tableau_recap">
            <thead>
    		<tr>
    			<th>Code</th>
    			<th>Volume taxe</th>
    			<th>Volume réintégré</th>
    			<th>Taux</th>
    			<th>Droits à payer</th>
    		</tr>
             </thead>
             <tbody>
                <?php foreach ($droits as $code => $droit) :  ?>
    		<tr class="alt">
                        <td><?php echo "$code"; ?></td>
    			<td><?php echoFloat( $droit->volume_taxe); ?></td>
    			<td><?php echoFloat( $droit->volume_reintegre); ?></td>
    			<td><?php echoFloat( $droit->taux); ?></td>
    			<td><?php echoFloat( $droit->payable); ?>&nbsp;€</td>
    		</tr>
    		<?php endforeach; ?>
	    </tbody>
    	</table>
        </div>
  	<?php endforeach; ?>
    	<a href="<?php echo url_for('drm_pdf', array('campagne_rectificative' => $drm->getCampagneAndRectificative())) ?>">Pdf</a>
        <div id="btn_etape_dr">
            <?php if($drm_suivante && $drm_suivante->isRectificative()): ?>
            <a href="<?php echo url_for('drm_init', array('campagne_rectificative' => $drm_suivante->getCampagneAndRectificative())) ?>" class="btn_suiv">
                <span>Valider la DRM suivante</span>
            </a>
            <?php else: ?>
            <a href="<?php echo url_for('drm_mon_espace') ?>" class="btn_suiv">
                <span>Retour à mon espace</span>
            </a>
            <?php endif; ?>
        </div>
     </div>    
    </section>
</section>
