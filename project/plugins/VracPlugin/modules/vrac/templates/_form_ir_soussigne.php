<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu" class="vracs">
	<?php include_component('vrac', 'etapes', array('vrac' => $form->getObject(), 'actif' => $etape)); ?>
	<form class="popup_form" method="post" action="<?php echo url_for('vrac_etape', array('sf_subject' => $form->getObject(), 'step' => $etape)) ?>">
		<?php echo $form->renderHiddenFields() ?>
		<?php echo $form->renderGlobalErrors() ?>
		<hr />
		<h2>Vendeur</h2>
		<hr />
		<div id="vendeur">
			<div>
                <?php echo $form['vendeur_type']->renderError() ?>
				<?php echo $form['vendeur_type']->renderLabel() ?>
                <?php echo $form['vendeur_type']->render(array('class' => 'famille')) ?>
			</div>
			<div>
                <?php echo $form['vendeur_identifiant']->renderError() ?>
				<?php echo $form['vendeur_identifiant']->renderLabel() ?>
				<?php echo $form['vendeur_identifiant']->render() ?>
			</div>
			<div> 
				<table>
					<!--<tr>
						<td>
							<?php echo $form['vendeur']['raison_sociale']->renderError() ?>
							<?php echo $form['vendeur']['raison_sociale']->renderLabel() ?>
							<?php echo $form['vendeur']['raison_sociale']->render() ?>
						</td>
						<td>
							<?php echo $form['vendeur']['nom']->renderError() ?>
							<?php echo $form['vendeur']['nom']->renderLabel() ?>
							<?php echo $form['vendeur']['nom']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['vendeur']['siret']->renderError() ?>
							<?php echo $form['vendeur']['siret']->renderLabel() ?>
							<?php echo $form['vendeur']['siret']->render() ?>
						</td>
						<td>
							<?php echo $form['vendeur']['cvi']->renderError() ?>
							<?php echo $form['vendeur']['cvi']->renderLabel() ?>
							<?php echo $form['vendeur']['cvi']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['vendeur']['adresse']->renderError() ?>
							<?php echo $form['vendeur']['adresse']->renderLabel() ?>
							<?php echo $form['vendeur']['adresse']->render() ?>
						</td>
						<td>
							<?php echo $form['vendeur']['code_postal']->renderError() ?>
							<?php echo $form['vendeur']['code_postal']->renderLabel() ?>
							<?php echo $form['vendeur']['code_postal']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['vendeur']['commune']->renderError() ?>
							<?php echo $form['vendeur']['commune']->renderLabel() ?>
							<?php echo $form['vendeur']['commune']->render() ?>
						</td>
						<td>
							<?php echo $form['vendeur']['telephone']->renderError() ?>
							<?php echo $form['vendeur']['telephone']->renderLabel() ?>
							<?php echo $form['vendeur']['telephone']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['vendeur']['fax']->renderError() ?>
							<?php echo $form['vendeur']['fax']->renderLabel() ?>
							<?php echo $form['vendeur']['fax']->render() ?>
						</td>
						<td>
							<?php echo $form['vendeur']['email']->renderError() ?>
							<?php echo $form['vendeur']['email']->renderLabel() ?>
							<?php echo $form['vendeur']['email']->render() ?>
						</td>
					</tr>-->
					<tr>
						<td>
							<?php echo $form['vendeur_assujetti_tva']->renderError() ?>
							<?php echo $form['vendeur_assujetti_tva']->renderLabel() ?>
							<?php echo $form['vendeur_assujetti_tva']->render() ?>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<p>Précision de l'adresse de stockage (si différente)</p>
			<div> 
				<table>
					<tr>
						<td>
							<?php echo $form['adresse_stockage']['adresse']->renderError() ?>
							<?php echo $form['adresse_stockage']['adresse']->renderLabel() ?>
							<?php echo $form['adresse_stockage']['adresse']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['adresse_stockage']['code_postal']->renderError() ?>
							<?php echo $form['adresse_stockage']['code_postal']->renderLabel() ?>
							<?php echo $form['adresse_stockage']['code_postal']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['adresse_stockage']['commune']->renderError() ?>
							<?php echo $form['adresse_stockage']['commune']->renderLabel() ?>
							<?php echo $form['adresse_stockage']['commune']->render() ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<hr />
		<h2>Acheteur</h2>
		<hr />
		<div id="acheteur">
			<div>
                <?php echo $form['acheteur_type']->renderError() ?>
				<?php echo $form['acheteur_type']->renderLabel() ?>
                <?php echo $form['acheteur_type']->render(array('class' => 'famille')) ?>
			</div>
			<div>
                <?php echo $form['acheteur_identifiant']->renderError() ?>
				<?php echo $form['acheteur_identifiant']->renderLabel() ?>
				<?php echo $form['acheteur_identifiant']->render() ?>
			</div>
			<div> 
				<!--<table>
					<tr>
						<td>
							<?php echo $form['acheteur']['raison_sociale']->renderError() ?>
							<?php echo $form['acheteur']['raison_sociale']->renderLabel() ?>
							<?php echo $form['acheteur']['raison_sociale']->render() ?>
						</td>
						<td>
							<?php echo $form['acheteur']['nom']->renderError() ?>
							<?php echo $form['acheteur']['nom']->renderLabel() ?>
							<?php echo $form['acheteur']['nom']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['acheteur']['siret']->renderError() ?>
							<?php echo $form['acheteur']['siret']->renderLabel() ?>
							<?php echo $form['acheteur']['siret']->render() ?>
						</td>
						<td>
							<?php echo $form['acheteur']['cvi']->renderError() ?>
							<?php echo $form['acheteur']['cvi']->renderLabel() ?>
							<?php echo $form['acheteur']['cvi']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['acheteur']['adresse']->renderError() ?>
							<?php echo $form['acheteur']['adresse']->renderLabel() ?>
							<?php echo $form['acheteur']['adresse']->render() ?>
						</td>
						<td>
							<?php echo $form['acheteur']['code_postal']->renderError() ?>
							<?php echo $form['acheteur']['code_postal']->renderLabel() ?>
							<?php echo $form['acheteur']['code_postal']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['acheteur']['commune']->renderError() ?>
							<?php echo $form['acheteur']['commune']->renderLabel() ?>
							<?php echo $form['acheteur']['commune']->render() ?>
						</td>
						<td>
							<?php echo $form['acheteur']['telephone']->renderError() ?>
							<?php echo $form['acheteur']['telephone']->renderLabel() ?>
							<?php echo $form['acheteur']['telephone']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['acheteur']['fax']->renderError() ?>
							<?php echo $form['acheteur']['fax']->renderLabel() ?>
							<?php echo $form['acheteur']['fax']->render() ?>
						</td>
						<td>
							<?php echo $form['acheteur']['email']->renderError() ?>
							<?php echo $form['acheteur']['email']->renderLabel() ?>
							<?php echo $form['acheteur']['email']->render() ?>
						</td>
					</tr>-->
					<tr>
						<td>
							<?php echo $form['acheteur_assujetti_tva']->renderError() ?>
							<?php echo $form['acheteur_assujetti_tva']->renderLabel() ?>
							<?php echo $form['acheteur_assujetti_tva']->render() ?>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<p>Précision de l'adresse de livraison (si différente)</p>
			<div> 
				<table>
					<tr>
						<td>
							<?php echo $form['adresse_livraison']['adresse']->renderError() ?>
							<?php echo $form['adresse_livraison']['adresse']->renderLabel() ?>
							<?php echo $form['adresse_livraison']['adresse']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['adresse_livraison']['code_postal']->renderError() ?>
							<?php echo $form['adresse_livraison']['code_postal']->renderLabel() ?>
							<?php echo $form['adresse_livraison']['code_postal']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['adresse_livraison']['commune']->renderError() ?>
							<?php echo $form['adresse_livraison']['commune']->renderLabel() ?>
							<?php echo $form['adresse_livraison']['commune']->render() ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<hr />
		<h2>Mandataire</h2>
		<hr />
		<div>
			<?php echo $form['mandataire_exist']->renderError() ?>
			<?php echo $form['mandataire_exist']->renderLabel() ?>
			<?php echo $form['mandataire_exist']->render() ?>
		</div>
		
		<div id="mandataire">
			<div>
                <?php echo $form['mandataire_identifiant']->renderError() ?>
				<?php echo $form['mandataire_identifiant']->renderLabel() ?>
				<?php echo $form['mandataire_identifiant']->render() ?>
			</div>
			<!--<div> 
				<table>
					<tr>
						<td>
							<?php echo $form['mandataire']['raison_sociale']->renderError() ?>
							<?php echo $form['mandataire']['raison_sociale']->renderLabel() ?>
							<?php echo $form['mandataire']['raison_sociale']->render() ?>
						</td>
						<td>
							<?php echo $form['mandataire']['nom']->renderError() ?>
							<?php echo $form['mandataire']['nom']->renderLabel() ?>
							<?php echo $form['mandataire']['nom']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['mandataire']['siret']->renderError() ?>
							<?php echo $form['mandataire']['siret']->renderLabel() ?>
							<?php echo $form['mandataire']['siret']->render() ?>
						</td>
						<td>
							<?php echo $form['mandataire']['carte_pro']->renderError() ?>
							<?php echo $form['mandataire']['carte_pro']->renderLabel() ?>
							<?php echo $form['mandataire']['carte_pro']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['mandataire']['adresse']->renderError() ?>
							<?php echo $form['mandataire']['adresse']->renderLabel() ?>
							<?php echo $form['mandataire']['adresse']->render() ?>
						</td>
						<td>
							<?php echo $form['mandataire']['code_postal']->renderError() ?>
							<?php echo $form['mandataire']['code_postal']->renderLabel() ?>
							<?php echo $form['mandataire']['code_postal']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['mandataire']['commune']->renderError() ?>
							<?php echo $form['mandataire']['commune']->renderLabel() ?>
							<?php echo $form['mandataire']['commune']->render() ?>
						</td>
						<td>
							<?php echo $form['mandataire']['telephone']->renderError() ?>
							<?php echo $form['mandataire']['telephone']->renderLabel() ?>
							<?php echo $form['mandataire']['telephone']->render() ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form['mandataire']['fax']->renderError() ?>
							<?php echo $form['mandataire']['fax']->renderLabel() ?>
							<?php echo $form['mandataire']['fax']->render() ?>
						</td>
						<td>
							<?php echo $form['mandataire']['email']->renderError() ?>
							<?php echo $form['mandataire']['email']->renderLabel() ?>
							<?php echo $form['mandataire']['email']->render() ?>
						</td>
					</tr>
				</table>
			</div>-->
		</div>
		<hr />
		<h2>Type de contrat</h2>
		<hr />
		<div id="contrat">
			<div>
                <?php echo $form['premiere_mise_en_marche']->renderError() ?>
				<?php echo $form['premiere_mise_en_marche']->renderLabel() ?>
				<?php echo $form['premiere_mise_en_marche']->render() ?>
			</div>
			<div>
                <?php echo $form['production_otna']->renderError() ?>
				<?php echo $form['production_otna']->renderLabel() ?>
				<?php echo $form['production_otna']->render() ?>
			</div>
			<div>
                <?php echo $form['apport_union']->renderError() ?>
				<?php echo $form['apport_union']->renderLabel() ?>
				<?php echo $form['apport_union']->render() ?>
			</div>
			<div>
                <?php echo $form['cession_interne']->renderError() ?>
				<?php echo $form['cession_interne']->renderLabel() ?>
				<?php echo $form['cession_interne']->render() ?>
			</div>
		</div>
		
        <div class="ligne_form_btn">
			<button class="btn_valider" type="submit">Etape Suivante</button>
		</div>
	</form>
</section>