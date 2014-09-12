<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'contrat')); ?>
<section id="contenu">
<section id="principal"  class="produit">
<div class="clearfix" id="application_dr">
    <h1>Contrat <a class="btn_ajouter" href="<?php echo url_for('interpro_upload_csv', array('id' => $interpro->get('_id'))) ?>">Importer les données GRC</a></h1>
    <div id="mon_compte">
        <?php include_partial('validation/form', array('form' => $formLogin))?>
    </div>
    <br /><br />
    <a href="<?php echo url_for('validation_comptes_csv') ?>">Liste des comptes au format CSV</a>
    <br /><br />
    <h1>Comptes fictifs</h1>
	<div class="tableau_ajouts_liquidations">
		<table class="tableau_recap">
			<thead>
				<tr>
					<?php include_partial('validation/itemHeader') ?>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; foreach($comptes_fictif as $compte_fictif): ?>
				<?php include_partial('validation/item', array('compte' => $compte_fictif, 'i' => $i)) ?>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	</div>  
    <h1>Comptes en attente</h1>
	<div class="tableau_ajouts_liquidations">
		<table class="tableau_recap">
			<thead>
				<tr>
					<?php include_partial('validation/itemHeader') ?>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; foreach($comptes_attente as $compte_attente): ?>
				<?php include_partial('validation/item', array('compte' => $compte_attente, 'i' => $i)) ?>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
</section>
</section>