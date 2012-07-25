<li<?php if ($last): ?> class="dernier<?php if ($isActive): ?> actif passe<?php endif ?>"<?php elseif($first): ?> class="premier<?php if ($isActive): ?> actif passe<?php endif ?>"<?php endif; ?>>
    <?php if ($isLink): ?>
        <a href="<?php echo url_for('vrac_etape', array('sf_subject' => $vrac, 'step' => $etape)) ?>"><?php echo $label ?></a>    
    <?php else: ?>
        <?php if($isActive): ?>
            <strong><?php echo $label ?></strong>
        <?php else: ?>
            <?php if ($last): ?><span><?php endif; ?>
            <span><?php echo $label ?></span>
            <?php if ($last): ?></span><?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</li>


   
