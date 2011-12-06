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

<?php include_partial('global/navTop'); ?>

<section id="contenu">
    <?php include_partial('drm_global/header'); ?>
    <?php include_component('drm_global', 'etapes', array('etape' => 'ajouts-liquidations', 'pourcentage' => '10')); ?>

    <section id="principal">

        <?php //include_partial('drm_mouvements_generaux/onglets', array('active' => 'mouvements-generaux')) ?>

        <div id="contenu_onglet">
            <div style="margin-bottom:30px;">
                <form class="labelForm" id="formAOP" action="<?php echo url_for('@drm_mouvements_generaux_save') ?>" method="post">
                    <input type="hidden" value="AOP" name="label" />
                    <h2 style="font-size: 16px;">AOP</h2>
                    <table class="table_mouv" id="listingAOP" width="100%">
                        <tr>
                            <th width="240">Appellation</th>
                            <th width="100">Couleur</th>
                            <th width="150">Dénomination</th>
                            <th width="100">Label</th>
                            <th width="80">Disponible</th>
                            <th width="80">Stock vide</th>
                            <th width="80">Pas de mouvement</th>
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
                    <a href="javascript:void(0)" class="addRow " id="aopRow" style="display: inline-block;width:100%;text-align:right;">Ajouter un nouveau produit</a>
                </form>
            </div>
            <div style="margin-bottom:30px;">
                <form class="labelForm" id="formIGP" action="<?php echo url_for('@drm_mouvements_generaux_save') ?>" method="post">
                    <input type="hidden" value="IGP" name="label" />
                    <h2 style="font-size: 16px;">IGP</h2>
                    <table class="table_mouv" id="listingIGP" width="100%">
                        <tr>
                            <th width="240">Appellation</th>
                            <th width="100">Couleur</th>
                            <th width="150">Dénomination</th>
                            <th width="100">Label</th>
                            <th width="80">Disponible</th>
                            <th width="80">Stock vide</th>
                            <th width="80">Pas de mouvement</th>
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
                    <a href="javascript:void(0)" class="addRow" id="igpRow" style="display: inline-block;width:100%;text-align:right;">Ajouter un nouveau produit</a>
                </form>
            </div>
            <div style="margin-bottom:30px;">
                <form class="labelForm" id="formVINSSANSIG" action="<?php echo url_for('@drm_mouvements_generaux_save') ?>" method="post">
                    <input type="hidden" value="VINSSANSIG" name="label" />
                    <h2 style="font-size: 16px;">Vins sans IG</h2>
                    <table class="table_mouv" id="listingVINSSANSIG" width="100%">
                        <tr>
                            <th width="240">Appellation</th>
                            <th width="120">Couleur</th>
                            <th width="150">Dénomination</th>
                            <th width="120">Label</th>
                            <th width="80">Disponible</th>
                            <th width="80">Stock vide</th>
                            <th width="80">Pas de mouvement</th>
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
                    <a href="javascript:void(0)" class="addRow ajouter" id="vinssansigRow" style="display: inline-block;width:100%;text-align:right;">Ajouter un nouveau produit</a>
                </form>
            </div>
        </div>
        <div id="btn_etape_dr">
            <a href="<?php echo url_for('@drm_informations') ?>" class="btn_prec">Précédent</a>
            <a id="nextStep" href="<?php echo url_for('drm_recap', ConfigurationClient::getCurrent()->declaration->labels->AOP) ?>" class="btn_suiv">Suivant</a>
        </div>

    </section>
</section>