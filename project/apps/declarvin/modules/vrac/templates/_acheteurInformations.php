<?php
use_helper('Display');
?>
<script type="text/javascript">
    $(document).ready(function() 
    { 
        init_informations('acheteur');       
       <?php
        if(!isset($numero_contrat))
        {
        ?>
        ajaxifyGet('modification','#vrac_acheteur_identifiant','#acheteur_modification_btn','#acheteur_informations');
        <?php
        }
        else
        {
        ?>        
        ajaxifyGet('modification',{field_0 : '#vrac_acheteur_identifiant',
                                   'type' : 'acheteur' ,
                                   'numero_contrat' : '<?php echo $numero_contrat;?>'
                                  }, '#acheteur_modification_btn','#acheteur_informations');
        <?php
        }
        ?>
    });
</script>
<table class="vendeur_infos">
	<tr>
		<td class="bold">Nom de l'acheteur*:&nbsp;</td>
		<td><?php display_field($acheteur,'nom'); ?></td>
	</tr>
	<tr>
		<td class="bold">Adresse*:&nbsp;</td>
		<td><?php  if($acheteur) display_field($acheteur->siege,'adresse');  ?></td>
	</tr>
	<tr>
		<td class="bold">N° CVI:&nbsp;</td>
		<td><?php display_field($acheteur,'cvi'); ?></td>
	</tr>
	<tr>
		<td class="bold">CP*:&nbsp;</td>
		<td><?php  if($acheteur) display_field($acheteur->siege,'code_postal');  ?></td>
	</tr>
	<tr>
		<td class="bold">N° ACCISE:&nbsp;</td>
		<td><?php display_field($acheteur,'no_accises'); ?></td>
	</tr>
	<tr>
		<td class="bold">Ville*:&nbsp;</td>
		<td><?php  if($acheteur) display_field($acheteur->siege,'commune');  ?></td>
	</tr>
	<tr>
		<td class="bold">TVA Intracomm.:&nbsp;</td>
		<td><?php display_field($acheteur,'no_tva_intracommunautaire'); ?></td>
	</tr>
</table>
