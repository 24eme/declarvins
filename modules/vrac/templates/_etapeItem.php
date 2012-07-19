<li>
	<?php if ($isLink): ?>
    <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => $etape)) ?>"><?php echo $label ?></a>    
    <?php else: ?>
        <?php if($isActive): ?><strong><?php endif; ?><?php echo $label ?><?php if($isActive): ?></strong><?php endif; ?>
    <?php endif; ?>
</li>


   
