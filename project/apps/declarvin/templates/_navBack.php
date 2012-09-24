<nav id="barre_navigation">
	<ul id="nav_principale">
		<?php if (!$sf_user->hasAttribute('interpro_id')): ?>
		<li<?php if ($active == 'admin'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin') ?>">Accueil</a>
		</li>
		<?php else: ?>
		<li<?php if ($active == 'operateurs'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@etablissement_login') ?>">Opérateurs</a>
		</li>
		<li<?php if ($active == 'parametrage'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@produits') ?>">Paramétrage</a>
		</li>
        <li<?php if ($active == 'comptes'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin_comptes') ?>">Comptes</a>
		</li>
		<?php endif; ?>
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
		<li class="etablissement_courant_admin"><a href="<?php echo url_for('@admin'); ?>"><span><?php if ($sf_user->hasAttribute('interpro_id')): ?><?php echo $sf_user->getInterpro()->nom ?><?php else: ?>Connexion<?php endif; ?></span></a></li>
		<li class="quitter"><a href="<?php echo url_for('@ac_vin_logout'); ?>"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
	</ul>
</nav>
<style>
#barre_sub_navigation:after {
    clear: both;
}
#barre_sub_navigation:before, #barre_sub_navigation:after {
    content: " ";
    display: block;
    font-size: 0;
    height: 0;
    visibility: hidden;
}
#barre_sub_navigation:before, #barre_sub_navigation:after {
    content: " ";
    display: block;
    font-size: 0;
    height: 0;
    visibility: hidden;
}
#barre_sub_navigation {
    background: none repeat scroll 0 0 #F1F1F1;
    border-bottom: 1px solid #E1E1E0;
    padding: 4px 4px 0;
}


#sub_nav {
    float: left;
    font-size: 14px;
    line-height: 35px;
    margin: 0 0 -10px;
    position: relative;
    text-align: center;
    top: -10px;
}
#sub_nav li {
    float: left;
    margin: 0 4px 0 0;
}
#sub_nav li.actif {
    font-weight: bold;
}
</style>
<nav id="barre_sub_navigation">
	<ul id="sub_nav">
		<?php if ($active == 'operateurs'): ?>
			<li<?php if ($subactive == 'etablissement'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@etablissement_login') ?>">DRM</a>
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
		<?php elseif ($active == 'comptes'): ?>
			<li<?php if ($subactive == 'comptes'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@admin_comptes') ?>">Tous les comptes</a>
			</li>	
			<li<?php if ($subactive == 'contrat'): ?> class="actif"<?php endif; ?>>
				<a href="<?php echo url_for('@validation_login') ?>">Contrat mandat</a>
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
