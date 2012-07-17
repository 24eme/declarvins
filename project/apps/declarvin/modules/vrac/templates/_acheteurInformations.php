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
        ajaxifyGet('modification',{field_0 : '#vrac_acheteur_identifiant', 'type': 'acheteur'},'#acheteur_modification_btn','#acheteur_informations');
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

<div class="vendeur_infos bloc_form">
    
    <div class="col">
        <div class="vracs_ligne_form ">
            <span>
                <label>Nom de l'acheteur* :</label>
                <?php display_field($acheteur,'nom'); ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <label>N° CVI :</label>
                <?php display_field($acheteur,'cvi'); ?>
            </span>
        </div>
        <div class="vracs_ligne_form">
            <span>
                <label>N° ACCISE :</label>
                <?php display_field($acheteur,'no_accises'); ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt " >
            <span>
                <label>TVA Intracomm. :</label>
                <?php display_field($acheteur,'no_tva_intracommunautaire'); ?>
            </span>
        </div>
    </div>
    
    <div class="col col_right">
        <div class="vracs_ligne_form">
            <span>
                <label>Adresse* :</label>
                <?php  if($acheteur) display_field($acheteur->siege,'adresse');  ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <label>CP* :</label>
                <?php  if($acheteur) display_field($acheteur->siege,'code_postal');  ?>
            </span>
        </div>
        <div class="vracs_ligne_form">
            <span>
                <label>Ville* :</label>
                <?php  if($acheteur) display_field($acheteur->siege,'commune');  ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span><br /></span>
        </div>
    </div>
</div>

