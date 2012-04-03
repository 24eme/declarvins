<nav id="barre_navigation">
	<ul id="nav_principale">
		<li<?php if ($active == 'admin'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin') ?>">Accueil</a>
		</li>
		<li<?php if ($active == 'contrat'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@validation_login') ?>">Contrat</a>
		</li>
	</ul>
	
	<ul id="actions_etablissement">
		<li class="etablissement_courant"><a href="#"><span>BO Top Moumoute</span></a></li>
		<li class="quitter"><a href="#"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
	</ul>
</nav>