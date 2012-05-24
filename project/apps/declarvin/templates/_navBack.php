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
        <li<?php if ($active == 'libelles'): ?> class="actif"<?php endif; ?>>
			<a href="<?php echo url_for('@admin_libelles') ?>">Libellés</a>
		</li>
		<?php endif; ?>
	</ul>
	
	<ul id="actions_etablissement">
		<?php if ($recherche): ?>
		<li class="popup_form">
			<form method="post" action="<?php echo url_for('@etablissement_login') ?>">
		        <?php echo $form->renderHiddenFields(); ?>
				<div class="ligne_form ligne_form_label">
		        <?php echo $form['etablissement']->render() ?>
			    <input type="submit" value="Ok" />
		        </div>
			</form>
			<script type="text/javascript">
			$(document).ready(function () {
				$( "#<?php echo $form['etablissement']->renderId() ?>" ).combobox();
			});
			</script>
		</li>
		<?php endif; ?>
		<li class="etablissement_courant_admin"><a href="#"><span><?php if ($sf_user->hasAttribute('interpro_id')): ?><?php echo $sf_user->getInterpro()->nom ?><?php else: ?>Connexion<?php endif; ?></span></a></li>
		<li class="quitter"><a href="#"><img src="/images/boutons/btn_quitter_etablissement.png" alt="Quitter cet établissement"></a></li>
	</ul>
</nav>