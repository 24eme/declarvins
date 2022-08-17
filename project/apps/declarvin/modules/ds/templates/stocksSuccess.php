<?php use_helper('Date'); ?>
<?php use_helper('Float'); ?>
<?php use_helper('Version'); ?>
<?php include_partial('ds/preTemplate'); ?>
<?php include_partial('ds/breadcrum', array('etablissement' => $etablissement)); ?>

<section id="principal">
    <?php include_partial('ds/etapes', array('ds' => $ds)); ?>
    <p>Dans cette étape, vous devez préciser les stocks pour l'ensemble des produits ci-dessous :</p>
    <form action="" method="post">
      <?php echo $form->renderHiddenFields(); ?>
    <div id="colonnes_dr">
        <div id="colonne_intitules">
      	  <p class="couleur" style="height: 20px;border:none; background:none;">&nbsp;</p>
      	  <p class="groupe" style="text-align: right;"><strong>Rappel du stock fin de mois <?php echo (format_date($ds->date_stock, 'MMMM yyyy', 'fr_FR')) ?></strong>&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_rappel_stock" title="Message aide"></a></p>

          <p class="groupe"><strong>Millésime <?php echo $ds->millesime ?></strong></p>

      		<div class="groupe demarrage-ouvert" data-groupe-id="1">
        		<ul style="display: block; padding-top:0px;">
              <li style="height: 21px;">Stock millésime <?php echo $ds->millesime ?> (vrac et conditionné)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_stock_courant" title="Message aide"></a></li>
        			<li style="height: 21px;"><strong>Dont</strong> Vrac libre à la vente&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_dontvrac_courant" title="Message aide"></a></li>
        		</ul>
      	  </div>

          <p class="groupe"><strong>Millésime <?php echo ($ds->millesime - 1) ?></span></strong></p>

    	<div class="groupe demarrage-ouvert" data-groupe-id="2">
    		<ul style="display: block; padding-top:0px;">
          <li style="height: 21px;">Stock millésime <?php echo ($ds->millesime -1) ?> (vrac et conditionné)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_stock_precedent" title="Message aide"></a></li>
    			<li style="height: 21px;"><strong>Dont</strong> Vrac libre à la vente&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_dontvrac_precedent" title="Message aide"></a></li>
    		</ul>
    	</div>

          <p class="groupe"><strong>Autre millésime <span style="font-size: 80%;">(<?php echo ($ds->millesime - 2) ?>, précédent et non millésimé)</span></strong></p>

        	<div class="groupe demarrage-ouvert" data-groupe-id="2">
        		<ul style="display: block; padding-top:0px;">
              <li style="height: 21px;">Stock autre millésime (vrac et conditionné)&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_stock_anterieur" title="Message aide"></a></li>
        			<li style="height: 21px;"><strong>Dont</strong> Vrac libre à la vente&nbsp;<a href="" class="msg_aide" data-msg="help_popup_ds_dontvrac_anterieur" title="Message aide"></a></li>
        		</ul>
        	</div>
    	   </div>

         <div id="col_saisies">

            <div id="col_saisies_cont" style="width: 381px;">

              <?php
                foreach($ds->declaration as $hash => $produit):
                  $libelle = $produit->libelle;
                  foreach($produit->detail as $detail => $stocks):
                    if ($stocks->denomination_complementaire) {
                      $libelle .= ' '.$stocks->denomination_complementaire;
                    }
              ?>

              <div class="col_recolte" data-all-active="1">
                      <h2 style="height: 20px;margin:0;font-size:15px;"><?php echo $libelle ?><?php if ($detail !== "DEFAUT") echo " (".$detail.")"; ?></h2>
                      <div class="col_cont">
                          <p style="font-size: 12px; text-align: center; height: 20px;"><?php echoFloat($stocks->stock_initial_millesime_courant) ?>&nbsp;hl</p>
                          <p class="groupe" style="height: 23px;">&nbsp;</p>
                          <div class="groupe" data-groupe-id="1">
                              <ul style="display: block;padding-top:0px;">
                                <li class="<?php echo isVersionnerCssClass($stocks, 'stock_declare_millesime_courant') ?>" style="height: 21px;"><?php echo $form[$hash][$detail]['stock_declare_millesime_courant']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                                <li class="<?php echo isVersionnerCssClass($stocks, 'dont_vraclibre_millesime_courant') ?>" style="height: 21px;"><?php echo $form[$hash][$detail]['dont_vraclibre_millesime_courant']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                              </ul>
                          </div>
                          <p class="groupe" style="height: 23px;">&nbsp;</p>
                          <div class="groupe" data-groupe-id="2">
                              <ul style="display: block;padding-top:0px;">
                                <li class="<?php echo isVersionnerCssClass($stocks, 'stock_declare_millesime_precedent') ?>" style="height: 21px;"><?php echo $form[$hash][$detail]['stock_declare_millesime_precedent']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                                <li class="<?php echo isVersionnerCssClass($stocks, 'dont_vraclibre_millesime_precedent') ?>" style="height: 21px;"><?php echo $form[$hash][$detail]['dont_vraclibre_millesime_precedent']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                              </ul>
                          </div>
                          <p class="groupe" style="height: 23px;">&nbsp;</p>
                          <div class="groupe" data-groupe-id="2">
                              <ul style="display: block;padding-top:0px;">
                                <li class="<?php echo isVersionnerCssClass($stocks, 'stock_declare_millesime_anterieur') ?>" style="height: 21px;"><?php echo $form[$hash][$detail]['stock_declare_millesime_anterieur']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                                <li class="<?php echo isVersionnerCssClass($stocks, 'dont_vraclibre_millesime_anterieur') ?>" style="height: 21px;"><?php echo $form[$hash][$detail]['dont_vraclibre_millesime_anterieur']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                              </ul>
                          </div>
                      </div>
              </div>
              <?php endforeach; endforeach; ?>

    </div>
    </div>
    </div>

      <div class="row" style="margin-top: 20px;margin-bottom: 20px;">
          <div class="col-xs-6">
              <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('ds_infos', $ds) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
          </div>
          <div class="col-xs-6 text-right">
              <button type="submit" class="btn btn-primary">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
          </div>
      </div>

    </form>
  </section>

<?php //echo $form ?>
