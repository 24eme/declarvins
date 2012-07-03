<?php
use_helper('Display');
?>
<script type="text/javascript">
    $(document).ready(function() 
    { 
        init_informations('vendeur');       
       <?php
        if(!isset($numero_contrat))
        {
        ?>
        ajaxifyGet('modification','#vrac_vendeur_identifiant','#vendeur_modification_btn','#vendeur_informations');
        <?php
        }
        else
        {
        ?>        
        ajaxifyGet('modification',{field_0 : '#vrac_vendeur_identifiant',
                                   'type' : 'vendeur' ,
                                   'numero_contrat' : "<?php echo $numero_contrat;?>"
                                  },'#vendeur_modification_btn','#vendeur_informations');    
        <?php
        }
        ?>
    });
</script>
<table class="vendeur_infos">
	<tr>
		<td class="bold">Nom du vendeur*:&nbsp;</td>
		<td><?php display_field($vendeur,'nom'); ?></td>
	</tr>
	<tr>
		<td class="bold">Adresse*:&nbsp;</td>
		<td><?php  if($vendeur) display_field($vendeur->siege,'adresse');  ?></td>
	</tr>
	<tr>
		<td class="bold">N° CVI:&nbsp;</td>
		<td><?php display_field($vendeur,'cvi'); ?></td>
	</tr>
	<tr>
		<td class="bold">CP*:&nbsp;</td>
		<td><?php  if($vendeur) display_field($vendeur->siege,'code_postal');  ?></td>
	</tr>
	<tr>
		<td class="bold">N° ACCISE:&nbsp;</td>
		<td><?php display_field($vendeur,'no_accises'); ?></td>
	</tr>
	<tr>
		<td class="bold">Ville*:&nbsp;</td>
		<td><?php  if($vendeur) display_field($vendeur->siege,'commune');  ?></td>
	</tr>
	<tr>
		<td class="bold">TVA Intracomm.:&nbsp;</td>
		<td><?php display_field($vendeur,'no_tva_intracommunautaire'); ?></td>
	</tr>
</table>
