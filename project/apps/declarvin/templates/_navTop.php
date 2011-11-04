<div style="width: 100%">
<ul style="width: 70%; float: left;">
	<li style="display: inline;"><a href="#"<?php if ($active == 'accueil'): ?> style="color:red;"<?php endif; ?>>Accueil</a> |</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'mon_profil'): ?> style="color:red;"<?php endif; ?>>&nbsp;Mon profil</a> |</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'vrac'): ?> style="color:red;"<?php endif; ?>>&nbsp;Vrac</a> |</li>
	<li style="display: inline;"><a href="<?php echo url_for('@drm_mon_espace') ?>"<?php if ($active == 'drm'): ?> style="color:red;"<?php endif; ?>>&nbsp;DRM</a> |</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'dai_ds'): ?> style="color:red;"<?php endif; ?>>&nbsp;DAI/DS</a> |</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'dr'): ?> style="color:red;"<?php endif; ?>>&nbsp;DR</a> |</li>
	<li style="display: inline;"><a href="#"<?php if ($active == 'divers'): ?> style="color:red;"<?php endif; ?>>&nbsp;Divers</a></li>
</ul>
<ul style="width: 30%; float: left;display: inline;">
	<li style="display: inline;">
		<?php include_component('tiers', 'choixTiers')?>
	</li>
	<li style="display: inline;"><a href="<?php echo url_for('@tiers') ?>">Sortir</a></li>
</ul>
<div style="clear: both">&nbsp;</div>
</div>