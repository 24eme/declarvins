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
			<?php include_partial('addContrat', array('detail' => $detail)) ?>	
			<?php
				if (isset($forms[$detail->getIdentifiant()])) { 
					foreach ($forms[$detail->getIdentifiant()] as $form) {
						include_partial('itemContrat', array('form' => $form));
					}
				}
			?>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</section>