<?php use_helper('Float'); ?>
<?php include_partial('global/navTop', array('active' => 'drm')); ?>


<section id="contenu">

    <?php include_partial('drm/header'); ?>

    <!-- #principal -->
    <section id="principal">
    	DRM Validé avec succès
    	<h2>Droits total</h2>
    	<p><span>CVO: </span><?php echo echoFloat($drm->getTotalCvo()) ?>€</p>
    	<p><span>Douane: </span><?php echo echoFloat($drm->getTotalDouane()) ?>€</p>
    	<h2>Droits par code</h2>
    	<table>
    		<tr>
    			<th align="left" width="150px">Code</th>
    			<th align="left" width="80px">CVO</th>
    			<th align="left" width="80px">Douane</th>
    		</tr>
    		<?php foreach ($drm->getTotalDroitByCode() as $code => $value): $value = $value->getRawValue(); ?>
    		<tr>
    			<td><?php echo $code ?></td>
    			<td><?php echo (isset($value['cvo']))? echoFloat($value['cvo']) : echoFloat(0.00); ?></td>
    			<td><?php echo (isset($value['douane']))? echoFloat($value['douane']) : echoFloat(0.00); ?></td>
    		</tr>
    		<?php endforeach; ?>
    	</table>
    	<a href="<?php echo url_for('@drm_pdf') ?>">Pdf</a>
    </section>
</section>
