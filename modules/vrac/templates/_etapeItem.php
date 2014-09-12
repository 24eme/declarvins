<li class="<?php if ($last): ?>dernier<?php endif; ?><?php if($first): ?>premier<?php endif; ?><?php if ($isActive): ?> actif passe<?php endif ?><?php if ($isPrev): ?> passe<?php endif ?>">
    <?php if ($isLink): ?>
    	<?php if ($last): ?><span><span><?php endif; ?>
        <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => $etape, 'etablissement' => $etablissement)) ?>"><?php echo $label ?></a>    
        <?php if ($last): ?></span></span><?php endif; ?>
    <?php else: ?>
        <?php if($isActive): ?>
            <?php if ($last): ?><span><?php endif; ?>
            <span><strong><?php echo $label ?></strong></span>
            <?php if ($last): ?></span><?php endif; ?>
        <?php else: ?>
            <?php if ($last): ?><span><?php endif; ?>
            <span><?php echo $label ?></span>
            <?php if ($last): ?></span><?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</li>


   
