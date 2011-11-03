<div style="width: 100%">
<ul style="width: 70%; float: left;">
	<li style="display: inline;"><a href="<?php echo url_for('@drm_informations') ?>"<?php if ($active == 'informations'): ?> style="color:red;"<?php endif; ?>>1. Informations</a> &raquo;</li>
	<li style="display: inline;"><a href="<?php echo url_for('@drm_mouvements_generaux') ?>"<?php if ($active == 'ajouts-liquidations'): ?> style="color:red;"<?php endif; ?>>2. Ajouts/Liquidations</a> &raquo;</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'aop'): ?> style="color:red;"<?php endif; ?>>3. AOP</a> &raquo;</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'igp'): ?> style="color:red;"<?php endif; ?>>4. IGP</a> &raquo;</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'vins-sans-ig'): ?> style="color:red;"<?php endif; ?>>5. Vins sans IG</a> &raquo;</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'validation'): ?> style="color:red;"<?php endif; ?>>6. Validation</a></li>
</ul>
<div style="width: 30%; float:left;">
vous avez saisi <strong><?php echo $pourcentage ?>%</strong>
</div>
<div style="clear: both">&nbsp;</div>
</div>