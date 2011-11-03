<ul style="width: 100%;">
	<li style="display: inline;"><a href="<?php echo url_for('@drm_mouvements_generaux') ?>"<?php if ($active == 'mouvements-generaux'): ?> style="color:red;"<?php endif; ?>>Mouvements Généraux</a>&nbsp;</li>
	<li style="display: inline;"><a href="<?php echo url_for('@drm_evolution') ?>"<?php if ($active == 'evolution'): ?> style="color:red;"<?php endif; ?>>Evolution</a></li>
</ul>