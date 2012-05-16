<nav id="barre_navigation">
	<ul id="nav_principale">
		<?php if (!$sf_user->hasAttribute('interpro_id')): ?>
		<li<?php if ($active == 'admin'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin') ?>">Accueil</a>
		</li>
		<?php else: ?>
		<li<?php if ($active == 'etablissement'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@etablissement_login') ?>">Etablissement</a>
		</li>
		<li<?php if ($active == 'produits'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@produits') ?>">Produits</a>
		</li>
		<li<?php if ($active == 'contrat'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@validation_login') ?>">Contrat</a>
		</li>
                <li<?php if ($active == 'comptes'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin_comptes') ?>">Comptes</a>
		</li>
		<?php endif; ?>
	</ul>
	
	<ul id="actions_etablissement">
		<li class="etablissement_courant_admin"><a href="#"><span><?php if ($sf_user->hasAttribute('interpro_id')): ?><?php echo $sf_user->getInterpro()->nom ?><?php else: ?>Connexion<?php endif; ?></span></a></li>
		<li class="quitter"><a href="#"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
	</ul>
</nav>