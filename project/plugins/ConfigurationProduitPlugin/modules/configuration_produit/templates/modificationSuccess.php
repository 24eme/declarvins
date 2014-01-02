<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'produits')); ?>
<style>
.popup_form .ligne_form {
    margin: 0 0 10px 0;
}
.popup_form .ligne_form td {
    width: 190px;
    padding: 0 5px 0 0;
    text-align: left;
}
.popup_form .ligne_form select {
    height: 22px;
    width: 54px;
}
.popup_form h2 {
    padding-top: 20px;
}
.popup_form table {
   display: inline;
}
.popup_form .radio_form  {
	display: inline-block;
	width: 83px;
}
.popup_form .radio_form  label {
	display: inline-block;
	width: 64px;
}
</style>
<section id="contenu">
	<section id="principal">
	<div class="clearfix" id="application_dr">
	    <h1>Modification du noeud <strong><?php echo $noeud ?></strong>.</h1>
	    <div id="popup_produit" class="popup_contenu">
			<form class="popup_form" id="form_ajout" action="<?php echo url_for('configuration_produit_modification', array('noeud' => $form->getObject()->getTypeNoeud(), 'hash' => str_replace('/', '-', $form->getHash()))) ?>" method="post">
				<?php echo $form->renderGlobalErrors() ?>
				<?php echo $form->renderHiddenFields() ?>
			    <div class="contenu_onglet">
			    
					<div class="ligne_form ">
						<span class="error"><?php echo $form['libelle']->renderError() ?></span>
						<?php echo $form['libelle']->renderLabel() ?>
						<?php echo $form['libelle']->render() ?>
					</div>
					<div class="ligne_form">
						<span class="error"><?php echo $form['code']->renderError() ?></span>
						<?php echo $form['code']->renderLabel() ?>
						<?php echo $form['code']->render() ?>
					</div>
					<?php if ($form->getObject()->hasDrmVrac()): ?>
			        <div class="ligne_form">
			        	<span class="error"><?php echo $form['drm_vrac']->renderError() ?></span>
			            <?php echo $form['drm_vrac']->renderLabel() ?>
			            <?php echo $form['drm_vrac']->render() ?>
			        </div>
					<?php endif; ?>
					
					<?php if ($form->getObject()->hasLabels()): ?>
			        <h2>Labels&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
					<div class="subForm contenu_onglet" id="formsLabel">
					<?php foreach ($form['noeud_labels'] as $subform): ?>
						<div class="ligne_form" data-key="<?php echo $subform->getName() ?>">
							<table>
								<tr>
									<td><span class="error"><?php echo $subform['code']->renderError() ?></span><?php echo $subform['code']->renderLabel() ?><br /><?php echo $subform['code']->render() ?></td>
									<td><span class="error"><?php echo $subform['label']->renderError() ?></span><?php echo $subform['label']->renderLabel() ?><br /><?php echo $subform['label']->render() ?></td>
									<td><br />&nbsp;<a href="javascript:void(0)" class="removeForm btn_suppr"></a></td>
								</tr>
							</table>
						</div>
					<?php endforeach; ?>
					</div>
					<input class="counteur" type="hidden" name="nb_label" value="<?php echo count($form['noeud_labels']) ?>" />
					<?php include_partial('templateformsLabel'); ?>
					<?php endif; ?>
					
					<?php if ($form->getObject()->hasDepartements()): ?>
					<h2>Départements&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
			        <div class="subForm contenu_onglet" id="formsDepartement">
			        	<p>Liste des départements :</p><br />
						<?php foreach ($form['noeud_departements'] as $subform): ?>
							<div class="ligne_form" data-key="<?php echo $subform->getName() ?>">
								<span class="error"><?php echo $subform['departement']->renderError() ?></span>
								<?php echo $subform['departement']->render() ?>
								&nbsp;<a href="javascript:void(0)" class="removeForm btn_suppr"></a>
							</div>
						<?php endforeach; ?>
					</div>
					<input class="counteur" type="hidden" name="nb_departement" value="<?php echo count($form['noeud_departements']) ?>" />
					<?php include_partial('templateformsDepartement'); ?>
					<?php endif; ?>
					
					<?php if ($form->getObject()->hasCvo()): ?>		
			        <h2>Cotisations interprofessionnelles&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
			        <div class="subForm contenu_onglet" id="formsCvo">
						<?php foreach ($form['noeud_droits_cvo'] as $subform): ?>
						<div class="ligne_form" data-key="<?php echo $subform->getName() ?>">
							<table>
								<tr>
									<td><span class="error"><?php echo $subform['date']->renderError() ?></span><?php echo $subform['date']->renderLabel() ?><br /><?php echo $subform['date']->render() ?></td>
									<td><span class="error"><?php echo $subform['code']->renderError() ?></span><?php echo $subform['code']->renderLabel() ?><br /><?php echo $subform['code']->render() ?></td>
									<td><span class="error"><?php echo $subform['libelle']->renderError() ?></span><?php echo $subform['libelle']->renderLabel() ?><br /><?php echo $subform['libelle']->render() ?></td>
									<td><span class="error"><?php echo $subform['taux']->renderError() ?></span><?php echo $subform['taux']->renderLabel() ?><br /><?php echo $subform['taux']->render() ?></td>
									<td><br />&nbsp;<a href="javascript:void(0)" class="removeForm btn_suppr"></a></td>
								</tr>
							</table>
						</div>
						<?php endforeach; ?>
					</div>
					<input class="counteur" type="hidden" name="nb_cvo" value="<?php echo count($form['noeud_droits_cvo']) ?>" />
					<?php include_partial('templateformsCvo'); ?>
					<?php endif; ?>
					
					<?php if ($form->getObject()->hasDouane()): ?>		
			        <h2>Droits circulation&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
			        <div class="subForm contenu_onglet" id="formsDouane">
						<?php foreach ($form['noeud_droits_douane'] as $subform): ?>
						<div class="ligne_form" data-key="<?php echo $subform->getName() ?>">
							<table>
								<tr>
									<td><span class="error"><?php echo $subform['date']->renderError() ?></span><?php echo $subform['date']->renderLabel() ?><br /><?php echo $subform['date']->render() ?></td>
									<td><span class="error"><?php echo $subform['code']->renderError() ?></span><?php echo $subform['code']->renderLabel() ?><br /><?php echo $subform['code']->render() ?></td>
									<td><span class="error"><?php echo $subform['libelle']->renderError() ?></span><?php echo $subform['libelle']->renderLabel() ?><br /><?php echo $subform['libelle']->render() ?></td>
									<td><span class="error"><?php echo $subform['taux']->renderError() ?></span><?php echo $subform['taux']->renderLabel() ?><br /><?php echo $subform['taux']->render() ?></td>
									<td><br />&nbsp;<a href="javascript:void(0)" class="removeForm btn_suppr"></a></td>
								</tr>
							</table>
						</div>
						<?php endforeach; ?>
					</div>
					<input class="counteur" type="hidden" name="nb_douane" value="<?php echo count($form['noeud_droits_douane']) ?>" />
					<?php include_partial('templateformsDouane'); ?>
					<?php endif; ?>
					
					<?php if ($form->getObject()->hasOIOC()): ?>		
			        <h2>OI/OC&nbsp;&nbsp;<a href="javascript:void(0)" class="addForm btn_ajouter"></a></h2>
			        <div class="subForm contenu_onglet" id="formsOrganisme">
						<?php foreach ($form['noeud_organismes'] as $subform): ?>
						<div class="ligne_form" data-key="<?php echo $subform->getName() ?>">
							<table>
								<tr>
									<td><span class="error"><?php echo $subform['date']->renderError() ?></span><?php echo $subform['date']->renderLabel() ?><br /><?php echo $subform['date']->render() ?></td>
									<td><span class="error"><?php echo $subform['oioc']->renderError() ?></span><?php echo $subform['oioc']->renderLabel() ?><br /><?php echo $subform['oioc']->render() ?></td>
									<td><br />&nbsp;<a href="javascript:void(0)" class="removeForm btn_suppr"></a></td>
								</tr>
							</table>
						</div>
						<?php endforeach; ?>
					</div>
					<input class="counteur" type="hidden" name="nb_organisme" value="<?php echo count($form['noeud_organismes']) ?>" />
					<?php include_partial('templateformsOrganisme'); ?>
					<?php endif; ?>
					
					<?php if ($form->getObject()->hasDefinitionDrm()): ?>
					<h2>Activation des lignes DRM</h2>
					<div class="subForm contenu_onglet" id="definition_drm">
						<?php $subform = $form['noeud_definition_drm']; ?>
						<div class="ligne_form">
							<table>
								<tr>
									<td></td>
									<td>Entrée</td>
									<td>Sortie</td>
								</tr>
								<tr>
									<td>Repli</td>
									<td><?php echo $subform['entree_repli']->render() ?><br /><span class="error"><?php echo $subform['entree_repli']->renderError() ?></span></td>
									<td><?php echo $subform['sortie_repli']->render() ?><br /><span class="error"><?php echo $subform['sortie_repli']->renderError() ?></span></td>
								</tr>
								<tr>
									<td>Déclassement</td>
									<td><?php echo $subform['entree_declassement']->render() ?><br /><span class="error"><?php echo $subform['entree_declassement']->renderError() ?></span></td>
									<td><?php echo $subform['sortie_declassement']->render() ?><br /><span class="error"><?php echo $subform['sortie_declassement']->renderError() ?></span></td>
								</tr>
							</table>
						</div>
					</div>
					<?php endif; ?>
					
					<div class="ligne_form_btn">
						<a name="annuler" class="btn_annuler btn_fermer" href="<?php echo url_for('configuration_produit') ?>">Annuler</a>
						<button name="valider" class="btn_valider" type="submit">Valider</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	</section>
</section>