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
        ajaxifyGet('modification',{field_0 : '#vrac_vendeur_identifiant', 'type': 'vendeur'},'#vendeur_modification_btn','#vendeur_informations');
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

<div id="vendeur_infos" class="bloc_form">    
    <div class="col">
        <div class="vracs_ligne_form">
            <span>
                  <label>Nom du vendeur* :</label>
                  <?php display_field($vendeur,'nom'); ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <label>N° CVI :</label>
                <?php display_field($vendeur,'cvi'); ?>
            </span>
        </div>
        <div class="vracs_ligne_form">
            <span>
                <label>N° ACCISE :</label>
                <?php display_field($vendeur,'no_accises'); ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt " >
            <span>
                <label>TVA Intracomm. :</label>
                <?php display_field($vendeur,'no_tva_intracommunautaire'); ?>
            </span>
        </div>
    </div>
    
    <div class="col">
        <div class="vracs_ligne_form">
            <span>
                <label>Adresse* :</label>
                <?php  if($vendeur) display_field($vendeur->siege,'adresse');  ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span>
                <label>CP* :</label>
                <?php  if($vendeur) display_field($vendeur->siege,'code_postal');  ?>
            </span>
        </div>
        <div class="vracs_ligne_form">
            <span>
                <label>Ville* :</label>
                <?php  if($vendeur) display_field($vendeur->siege,'commune');  ?>
            </span>
        </div>
        <div class="vracs_ligne_form vracs_ligne_form_alt">
            <span><br /></span>
        </div>
    </div>
</div>
