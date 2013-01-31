<?php include_component('global', 'navBack', array('active' => 'comptes', 'subactive' => 'partenaires')); ?>
<section id="contenu">
<section id="principal"  class="produit">
<div class="clearfix" id="application_dr">
	<h1>Partenaire&nbsp;<a class="btn_ajouter" href="<?php echo url_for("compte_partenaire_ajout") ?>">Ajouter</a></h1>
	<div class="tableau_ajouts_liquidations">
		<table class="tableau_recap">
			<thead>
			<?php include_partial('ComptePartenaire/itemHeader') ?>
			</thead>
			<tbody>
			<?php $i = 0; foreach($comptes->rows as $compte): ?>
			<?php include_partial('ComptePartenaire/item', array('compte' => $compte, 'i' => $i)) ?>
			<?php $i++; endforeach; ?>
			</tbody>
		</table>
	</div>    
</div>
</section>
</section>