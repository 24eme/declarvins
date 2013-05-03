<script type="text/javascript">
    var familles = '<?php echo json_encode(EtablissementFamilles::getFamillesForJs()) ?>';
    var sousFamilleSelected = '<?php echo $form['sous_famille']->getValue() ?>';
</script>

<section id="contenu">
    <h1><strong>Étape 2 :</strong> Informations relatives à vos établissements</h1>
    <?php $intro = (count($contrat->etablissements) > 1) ? "vos établissements" : "votre établissement" ; ?>
    <p class="intro">Veuillez remplir les informations concernant <?php echo $intro; ?>  :</p>
    
    <div id="principal">
        <ul id="onglets_principal">
            <?php
            foreach ($contrat->etablissements as $etablissement) {

                $etablissementTitle = "Etablissement";
                $etablissementTitle .= (count($contrat->etablissements) > 1 && ($etablissement->getKey() > 0)) ? " ".($etablissement->getKey() + 1) : "";

                $class = ($etablissement->getKey() == $form->getObject()->getKey()) ? 'class="actif"' : '';

                echo '<li ' . $class . '><strong>' . $etablissementTitle . '</strong></li>';
            }
            ?>
        </ul>
		
        <?php
        foreach ($contrat->etablissements as $etablissement):
            if ($etablissement->getKey() == $form->getObject()->getKey()):
                ?>

                <div id="contenu_onglet">

                <form id="creation_compte" method="post" action="<?php echo ($recapitulatif) ? url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey(), 'recapitulatif' => 1)) : url_for('contrat_etablissement_modification', array('indice' => $etablissement->getKey())); ?>">
                    <?php include_partial('contrat/formEtablissement', array('form' => $form, 'new' => false, "recapitulatif" => ($recapitulatif == 1))); ?>
                </form>

                </div>

                <?php
            endif;
        endforeach;
        ?>
        
    </div>
    <div id="btn_etape_dr">
    	<?php if ($indice > 0): ?>
    	<a href="<?php echo url_for('contrat_etablissement_modification', array('indice' => $indice - 1)) ?>" class="btn_prec"><span>Précédent</span></a>
    	<?php else: ?>
        <a href="<?php echo url_for('contrat_nouveau', array('nocontrat' => $contrat->no_contrat)) ?>" class="btn_prec"><span>Précédent</span></a>
        <?php endif; ?>
    </div>
</section>