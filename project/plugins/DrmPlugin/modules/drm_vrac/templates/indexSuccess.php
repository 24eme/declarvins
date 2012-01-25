<script type="text/javascript">
    $(document).ready( function()
    {
        $('#subForm').live('submit', function () {
            var id = $(this).parents('div').attr('id');
            $.post($(this).attr('action'), $(this).serializeArray(),
            	function (data) {
                	if(data.success) {
                    	document.location.href = data.url;
                	} else {
                		$('#'+id).html(data.content);
                		var linkAction = $('a[data-popup=#'+id+']');
                		$.initPopup(linkAction);
                	}
            	}, "json"
            );
            return false;
        });
    })
</script>
<?php include_partial('global/navTop', array('active' => 'vrac')); ?>
<section id="contenu">
	<?php if ($details->count() > 0): ?>
	<table width="100%">
		<thead>
			<tr>
				<th align="left">Produit</th>
				<th width="150px">Contrat</th>
				<th width="200px">Volume</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($details as $detail): ?>
			<tr>
				<td><?php echo $detail->getRawValue() ?></td>
				<td align="center">
                    <div class="btn">
						<a href="<?php echo url_for('vrac_ajout_contrat', $detail) ?>" class="btn_ajouter btn_popup" data-popup-ajax="true" data-popup="#popup_ajout_contrat_<?php echo $detail->getIdentifiant() ?>" data-popup-config="configAjoutProduit"></a>
					</div>
				</td>
				<td align="center"></td>
			</tr>	
			<?php foreach ($detail->getVrac() as $vrac): ?>
			<tr>
				<td></td>
				<td align="center">
					<span><?php echo $vrac->getNumero() ?></span>
				</td>
				<td align="center"></td>
			</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</section>