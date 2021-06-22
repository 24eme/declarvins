<?php use_helper('Date'); ?>
<?php use_helper('Float'); ?>
<?php include_partial('dsnegoce/preTemplate'); ?>
<?php include_partial('dsnegoce/breadcrum', array('etablissement' => $etablissement)); ?>

<section id="principal">
    <?php include_partial('dsnegoce/etapes', array('dsnegoce' => $dsnegoce)); ?>
    <p>Dans cette étape, vous devez préciser les stocks pour l'ensemble des produits ci-dessous :</p>
    <form action="" method="post">
      <?php echo $form->renderHiddenFields(); ?>
    <div id="colonnes_dr">
        <div id="colonne_intitules">
      	  <p class="couleur" style="height: 20px;border:none; background:none;">&nbsp;</p>
      	  <p class="groupe" style="text-align: right;"><strong>Rappel du stock fin de mois de <?php echo (format_date($dsnegoce->date_stock, 'MMMM yyyy', 'fr_FR')) ?></strong></p>

          <p class="groupe"><strong>Millésime <?php echo $dsnegoce->millesime ?></strong></p>

      		<div class="groupe demarrage-ouvert" data-groupe-id="1">
        		<ul style="display: block; padding-top:0px;">
              <li style="height: 21px;">Stock</li>
        			<li style="height: 21px;"><strong>Dont</strong> Vrac libre à la vente</li>
        		</ul>
      	  </div>

          <p class="groupe"><strong>Autre millésime <span style="font-size: 80%;">(<?php echo ($dsnegoce->millesime - 1) ?>, précédent et non millésimé)</span></strong></p>

        	<div class="groupe demarrage-ouvert" data-groupe-id="2">
        		<ul style="display: block; padding-top:0px;">
              <li style="height: 21px;">Stock</li>
        			<li style="height: 21px;"><strong>Dont</strong> Vrac libre à la vente</li>
        		</ul>
        	</div>
    	   </div>

         <div id="col_saisies">

            <div id="col_saisies_cont" style="width: 381px;">

              <?php
                foreach($dsnegoce->declaration as $hash => $produit):
                  $libelle = $produit->libelle;
                  foreach($produit->detail as $detail => $stocks):
                    if ($stocks->denomination_complementaire) {
                      $libelle .= ' '.$stocks->denomination_complementaire;
                    }
              ?>

              <div class="col_recolte" data-all-active="1">
                      <h2 style="height: 20px;margin:0;font-size:15px"><?php echo $libelle ?></h2>
                      <div class="col_cont">
                          <p style="font-size: 12px; text-align: center; height: 20px;"><?php echoFloat($stocks->stock_initial_millesime_courant) ?></p>
                          <p class="groupe" style="height: 23px;">&nbsp;</p>
                          <div class="groupe" data-groupe-id="1">
                              <ul style="display: block;padding-top:0px;">
                                <li class="" style="height: 21px;"><?php echo $form[$hash][$detail]['stock_declare_millesime_courant']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                                <li class="" style="height: 21px;"><?php echo $form[$hash][$detail]['dont_vraclibre_millesime_courant']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                              </ul>
                          </div>
                          <p class="groupe" style="height: 23px;">&nbsp;</p>
                          <div class="groupe" data-groupe-id="2">
                              <ul style="display: block;padding-top:0px;">
                                <li class="" style="height: 21px;"><?php echo $form[$hash][$detail]['stock_declare_millesime_anterieur']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
                                <li class="" style="height: 21px;"><?php echo $form[$hash][$detail]['dont_vraclibre_millesime_anterieur']->render(array('class' => 'num num_float num_light', 'style' => 'height: 20px; width: 88px;')) ?></li>
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
              <a class="btn btn-default" tabindex="-1" href="<?php echo url_for('dsnegoce_infos', $dsnegoce) ?>"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Étape précédente</a>
          </div>
          <div class="col-xs-6 text-right">
              <button type="submit" class="btn btn-primary">Étape suivante&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
          </div>
      </div>

    </form>
  </section>

<?php //echo $form ?>
