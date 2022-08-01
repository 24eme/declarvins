<nav id="barre_navigation">
	<ul id="nav_principale">
		<li<?php if ($active == 'operateurs'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@etablissement_login') ?>">Opérateurs</a>
		</li>
		<li<?php if ($active == 'parametrage'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@configuration_produit') ?>">Paramétrage</a>
		</li>
        <li<?php if ($active == 'comptes'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin_comptes') ?>">Comptes</a>
		</li>
		<?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
        <li<?php if ($active == 'statistiques'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@statistiques_bilan_drm') ?>">Rapports</a>
		</li>
		<li>
			<a href="/exports/<?php echo str_replace('INTERPRO-', '', $sf_user->getCompte()->getGerantInterpro()->_id) ?>/">Exports</a>
		</li>
        <li>
			<a href="/metabase/">Métabase</a>
		</li>
		<?php endif; ?>
	</ul>
</nav>

<nav id="barre_sub_navigation">
	<ul id="sub_nav">
		<?php if ($active == 'operateurs'): ?>
			<li<?php if ($subactive == 'etablissement'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@etablissement_login') ?>">DRM</a>
			</li>
			<li<?php if ($subactive == 'daids'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@etablissement_login_daids') ?>">DAI/DS</a>
			</li>
			<li<?php if ($subactive == 'vrac'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('vrac_admin') ?>">Contrat interprofessionnel</a>
			</li>
            <?php if ($configuration->isApplicationOuverte($interpro->_id, 'sv12')): ?>
			<li<?php if ($subactive == 'sv12'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('sv12') ?>">SV12</a>
			</li>
			<?php endif; ?>
			<?php if ($configuration->isApplicationOuverte($interpro->_id, 'factures')): ?>
			<li<?php if ($subactive == 'factures'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('facture') ?>">Factures</a>
			</li>
			<?php endif; ?>
			<li<?php if ($subactive == 'dsnegoce'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@etablissement_login_dsnegoce') ?>">DS Négoce</a>
			</li>
			<li<?php if ($subactive == 'profil'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@etablissement_profil_login') ?>">Profil</a>
			</li>
			<li<?php if ($subactive == 'subvention'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@subvention') ?>">Aides Occitanie</a>
			</li>
		<?php elseif ($active == 'parametrage'): ?>
			<li<?php if ($subactive == 'produits'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@configuration_produit') ?>">Produits</a>
			</li>
	        <li<?php if ($subactive == 'douanes'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@admin_douanes') ?>">Douanes</a>
			</li>
	        <li<?php if ($subactive == 'libelles'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@admin_libelles') ?>">Libellés</a>
			</li>
	        <li<?php if ($subactive == 'volumes'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@interpro_upload_csv_volumes_bloques') ?>">Volumes bloqués</a>
			</li>
		<?php elseif ($active == 'comptes'): ?>
			<?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
			<li<?php if ($subactive == 'comptes'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@admin_comptes') ?>">Opérateurs</a>
			</li>
			<?php endif; ?>
			<li<?php if ($subactive == 'contrat'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@validation_login') ?>">Déclarants / Contrats d'inscription</a>
			</li>
			<li<?php if ($subactive == 'partenaires'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@partenaire_comptes') ?>">Partenaires</a>
			</li>
			<li<?php if ($subactive == 'oioc'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@oioc_comptes') ?>">OIOC</a>
			</li>
		<?php elseif ($active == 'statistiques'): ?>
			<?php if($sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)): ?>
			<li<?php if ($subactive == 'bilan_drm'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@statistiques_bilan_drm') ?>">Bilan DRM</a>
			</li>
			<li<?php if ($subactive == 'demat_drm'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('statistiques_demat_drm') ?>">Démat. DRM</a>
			</li>
			<?php endif; ?>
		<?php endif; ?>
	</ul>
</nav>

<nav id="sous_barre_navigation">
	<ul id="actions_etablissement">
		<li class="etablissement_courant_admin"><a href="<?php echo url_for('@admin'); ?>"><span><?php echo $sf_user->getCompte() ?></span></a></li>
		<li class="quitter"><a href="<?php echo url_for('@ac_vin_logout'); ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
    </ul>
</nav>

<?php if ($sf_user->hasFlash('notice')){ ?>
    <div id="flash_message">
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice'); ?></div>
    </div>
<?php } ?>

<?php if ($sf_user->hasFlash('error')){ ?>
    <div id="flash_message">
        <div class="flash_error"><?php echo $sf_user->getFlash('error'); ?></div>
    </div>
<?php } ?>
