<nav id="barre_navigation">
	<ul id="nav_principale">
		<li<?php if ($active == 'operateurs'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@etablissement_login') ?>">Opérateurs</a>
		</li>
		<li<?php if ($active == 'parametrage'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@produits') ?>">Paramétrage</a>
		</li>
        <li<?php if ($active == 'comptes'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin_comptes') ?>">Comptes</a>
		</li>
        <li<?php if ($active == 'alertes'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@alertes?reset_filters=true') ?>">Alertes</a>
		</li>
	</ul>
	<ul id="actions_etablissement">
		<?php if ($recherche && 1==2): // on masque la recherche intensionnellement ?>
		<li id="bloc_admin_etablissement_choice" class="popup_form">
			<form method="post" action="<?php echo url_for('@etablissement_login') ?>" id="select_etablissement" >
                                <?php echo $form->renderHiddenFields(); ?>
				<div class="ligne_form ligne_form_label">
                                <?php echo $form['etablissement']->render() ?>
                                <input type="submit" value="Ok" />
		        </form>
			<script type="text/javascript">
			$(document).ready(function () {
				$( "#<?php echo $form['etablissement']->renderId() ?>" ).combobox();
                        });
			</script>
		</li>
		<?php endif; ?>
		<li class="etablissement_courant_admin"><a href="<?php echo url_for('@admin'); ?>"><span><?php echo $sf_user->getCompte() ?></span></a></li>
		<li class="quitter"><a href="<?php echo url_for('@ac_vin_logout'); ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
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
			<li<?php if ($subactive == 'profil'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@etablissement_profil_login') ?>">Profil</a>
			</li>
		<?php elseif ($active == 'parametrage'): ?>
			<li<?php if ($subactive == 'produits'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@produits') ?>">Produits</a>
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
				<a href="<?php echo url_for('@validation_login') ?>">Déclarants / Contrats mandat</a>
			</li>		
			<li<?php if ($subactive == 'partenaires'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@partenaire_comptes') ?>">Partenaires</a>
			</li>		
		<?php endif; ?>
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
