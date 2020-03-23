<?php include_component('global', 'navBack', array('active' => 'parametrage', 'subactive' => 'libelles')); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
    		<h1>Messages</h1>
    		<?php include_partial('tableLibelles', array('object' => $messages, 'type' => 'messages')) ?>
    		<!-- <h1>Droits</h1> -->
    		<?php //include_partial('tableLibelles', array('object' => $droits, 'type' => 'droits')) ?>
    		<h1>Labels</h1>
    		<?php include_partial('tableLibelles', array('object' => $labels, 'type' => 'labels')) ?>
    		<h1>Controles</h1>
    		<?php include_partial('tableLibelles', array('object' => $controles, 'type' => 'controles')) ?>
    		<h1>Contrat Vrac</h1>
	    	<div class="tableau_ajouts_liquidations">
				<table class="tableau_recap">
					<thead>
						<tr>
							<th><strong>Code</strong></th>
							<th><strong>Libellé</strong></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td rowspan="<?php echo count($configurationVrac->clauses); ?>">Clauses</td>
<?php foreach ($configurationVrac->clauses as $k => $c) : ?>
								<td><?php echo $c->nom." : ".$c->description; ?></td>
								<td class="actions"><a class="btn_modifier"
										href="<?php echo url_for('admin_libelles_edit', array('type' => 'vrac', 'key' => 'clauses/'.$k)) ?>">Edit</a>
								</td>
</tr><tr>
<?php endforeach; ?>
					</tr>
					<tr class="alt">
						<td>Informations complémentaires</td>
						<td><?php echo $configurationVrac->informations_complementaires; ?></td>
						<td class="actions"><a class="btn_modifier"
							href="<?php echo url_for('admin_libelles_edit', array('type' => 'vrac', 'key' => 'informations_complementaires')) ?>">Edit</a>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</section>