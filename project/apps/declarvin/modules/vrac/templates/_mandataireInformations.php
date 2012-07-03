<?php
use_helper('Display');
?>
<script type="text/javascript">
    $(document).ready(function() 
    { 
        init_informations('mandataire');       
       <?php
        if(!isset($numero_contrat))
        {
       ?>
        ajaxifyGet('modification','#vrac_mandataire_identifiant','#mandataire_modification_btn','#mandataire_informations'); 
       <?php
        }
        else
        {
       ?>        
        ajaxifyGet('modification',{field_0 : '#vrac_mandataire_identifiant',
                                   'type' : 'mandataire' ,
                                   'numero_contrat' : '<?php echo $numero_contrat;?>'
                                  },'#mandataire_modification_btn','#mandataire_informations');           
       <?php
        }
       ?>
    });
</script>
<table class="mandataire_infos">
	<tr>
		<td class="bold">Nom du mandataire*:&nbsp;</td>
		<td><?php display_field($mandataire,'nom'); ?></td>
	</tr>
	<tr>
		<td class="bold">N° carte professionnelle:&nbsp;</td>
		<td><?php //display_field($mandataire,'carte_pro'); VOIR AVEC TANGUI ?></td>
	</tr>
	<tr>
		<td class="bold">Adresse:&nbsp;</td>
		<td><?php  if($mandataire) display_field($mandataire->siege,'adresse');  ?></td>
	</tr>
	<tr>
		<td class="bold">CP:&nbsp;</td>
		<td><?php  if($mandataire) display_field($mandataire->siege,'code_postal');  ?></td>
	</tr>
	<tr>
		<td class="bold">Ville:&nbsp;</td>
		<td><?php  if($mandataire) display_field($mandataire->siege,'commune');  ?></td>
	</tr>
</table>
