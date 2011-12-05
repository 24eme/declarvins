<script type="text/javascript">
$(document).ready( function()
{
	$('.addRow').click(function() {
		$('.labelForm')
		$('.addRow').hide();
		var label = "";
		if ($(this).attr('id') == 'aopRow') {
			label = 'AOP';
		} else if ($(this).attr('id') == 'igpRow') {
			label = 'IGP';
		} else {
			label = 'VINSSANSIG';
		}
		$.post(
			"<?php echo url_for('drm_mouvements_generaux_add_form') ?>", 
			{label: label},
			function(data) {
				$(data).insertAfter($("#listing"+label+" tr:last"));
			}
		);
	});
	$('.deleteRow').live('click', function() {
		$('#currentForm').remove();
		$('.addRow').show();
	});
	$('#subForm').live('submit', function () {
		alert('nop');
        $.post($(this).attr('action'), $(this).serializeArray(), function (data) {
        	$("#currentForm").replaceWith(data);
        });
    	$('.addRow').show();
        return false;
    });
	$('.labelForm').live('submit', function () {
        return false;
    });
	$('#nextStep').click(function () {
		$(".labelForm").each(function(){
			$.post($(this).attr('action'), $(this).serializeArray());
		});
		return true;
    });
})
function truc(form)
{
	$.post($(form).attr('action'), $(form).serializeArray(), function (data) {
    	$("#currentForm").replaceWith(data);
    });
	$('.addRow').show();
    return false;
}
</script>
<section id="contenu">
	<?php include_partial('global/navTop', array('active' => 'drm')) ?>
	<div id="creation_compte" style="width:100%;">
		<h1>Déclaration Récapitulative Mensuelle</h1>
		<p>DRM 2011 - MARS</p>
		<br />
		<?php include_partial('drm/etapes', array('active' => 'ajouts-liquidations', 'pourcentage' => '10')) ?>
		<?php include_partial('drm/onglets', array('active' => 'mouvements-generaux')) ?>
		<br />
		<div style="margin-bottom:30px;">
			<form class="labelForm" id="formAOP" action="<?php echo url_for('@drm_mouvements_generaux_save') ?>" method="post">
				<input type="hidden" value="AOP" name="label" />
				<h2>AOP</h2>
				<table id="listingAOP" width="100%">
					<tr>
						<th>Appellation</th>
						<th>Couleur</th>
						<th>Dénomination</th>
						<th>Label</th>
						<th>Disponible</th>
						<th>Stock vide</th>
						<th>Pas de mouvement</th>
						<th></th>
					</tr>
					<?php
						if ($aopForms):
							echo $aopForms->renderHiddenFields();
							foreach ($aopForms->getEmbeddedForms() as $key => $aopForm): 
					?>
						<?php include_partial('produitLigneModificationForm', array('form' => $aopForms[$key], 'object' => $aopForm->getObject(), 'appellation' => 'AOP')) ?>
					<?php 
							endforeach;
						endif;
					?>
				</table>
				<a href="javascript:void(0)" class="addRow" id="aopRow" style="display: inline-block;width:100%;text-align:right;">Add row</a>
			</form>
		</div>
		<div style="margin-bottom:30px;">
			<form class="labelForm" id="formIGP" action="<?php echo url_for('@drm_mouvements_generaux_save') ?>" method="post">
				<input type="hidden" value="IGP" name="label" />
				<h2>IGP</h2>
				<table id="listingIGP" width="100%">
					<tr>
						<th>Appellation</th>
						<th>Couleur</th>
						<th>Dénomination</th>
						<th>Label</th>
						<th>Disponible</th>
						<th>Stock vide</th>
						<th>Pas de mouvement</th>
						<th></th>
					</tr>
					<?php 
						if ($igpForms):
							echo $igpForms->renderHiddenFields();
							foreach ($igpForms->getEmbeddedForms() as $key => $igpForm): 
					?>
						<?php include_partial('produitLigneModificationForm', array('form' => $igpForms[$key], 'object' => $igpForm->getObject(), 'appellation' => 'IGP')) ?>
					<?php 
							endforeach;
						endif;
					?>
				</table>
				<a href="javascript:void(0)" class="addRow" id="igpRow" style="display: inline-block;width:100%;text-align:right;">Add row</a>
			</form>
		</div>
		<div style="margin-bottom:30px;">
			<form class="labelForm" id="formVINSSANSIG" action="<?php echo url_for('@drm_mouvements_generaux_save') ?>" method="post">
				<input type="hidden" value="VINSSANSIG" name="label" />
				<h2>Vins sans IG</h2>
				<table id="listingVINSSANSIG" width="100%">
					<tr>
						<th>Appellation</th>
						<th>Couleur</th>
						<th>Dénomination</th>
						<th>Label</th>
						<th>Disponible</th>
						<th>Stock vide</th>
						<th>Pas de mouvement</th>
						<th></th>
					</tr>
					<?php 
						if ($vinssansigForms):
							echo $vinssansigForms->renderHiddenFields();
							foreach ($vinssansigForms->getEmbeddedForms() as $key => $vinssansigForm): 
					?>
						<?php include_partial('produitLigneModificationForm', array('form' => $vinssansigForms[$key], 'object' => $vinssansigForm->getObject(), 'appellation' => 'VINSSANSIG')) ?>
					<?php 
							endforeach;
						endif;
					?>
				</table>
				<a href="javascript:void(0)" class="addRow" id="vinssansigRow" style="display: inline-block;width:100%;text-align:right;">Add row</a>
			</form>
		</div>
		<div style="width:100%; float: left;">
			<div style="width: 50%; float: left;">
				<a href="<?php echo url_for('@drm_informations') ?>" style="text-align: left;">&laquo; Précédent</a>
			</div>
			<div style="width: 50%; float: left; text-align: right;">
				<a id="nextStep" href="<?php echo url_for('@drm_evolution') ?>" style="text-align: right;">Suivant &raquo;</a>
			</div>
		</div>
	</div>
</section>