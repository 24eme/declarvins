<div id="contenu">
    <div id="rub_contrats">
        <div id="principal">
        <?php include_partial('headerVrac', array('vrac' => $vrac,'actif' => 4)); ?>        
            <div id="contenu_etape"> 
                <form id="vrac_validation" method="post" action="<?php echo url_for('vrac_validation',$vrac) ?>">

                    <h2>Récapitulatif de la saisie</h2>

                    <?php include_partial('showContrat', array('vrac' => $vrac)); ?>
                    <div class="btnValidation">
                        <span>&nbsp;</span>
                        <button class="btn_majeur btn_gris" type="submit">Saisir un nouveau contrat</button>                            
                    </div>

                </form>
            </div>
        </div>
        <?php include_partial('colonne', array('vrac' => $vrac)); ?>
    </div>
</div>