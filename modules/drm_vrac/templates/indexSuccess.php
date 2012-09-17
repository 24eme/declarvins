<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu" class="drm_vracs">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'vrac', 'pourcentage' => '30')); ?>

    <section id="principal">
        <div id="application_dr">
            
                <?php if ($details->count() > 0): ?>
                    
                        <?php
                        foreach ($details as $detail) {
                            
                            ?>
                            <table width="100%" class="contrat_vracs">
                            <?php

                            if (isset($noContrats[$detail->getIdentifiantHTML()]) && $noContrats[$detail->getIdentifiantHTML()]) {
                            	if($etablissement->hasDroit(EtablissementDroit::DROIT_VRAC) || $sf_user->hasCredential(myUser::CREDENTIAL_ADMIN)) {
                                	echo '<tr>
                                        <td class="libelle">' . $detail->getLibelle(ESC_RAW) . '</td>
                                      </tr>
                                      <tr class="contenu">
                                        <td align="center" >Pas de contrat défini pour ce produit.<br/>Merci de contacter votre interpro.<br /><br /><a href="'.url_for('vrac_etablissement', $etablissement).'">Saisir un contrat interprofessionnel</a></td>
                                      </tr>';
                            		
                            	} else {
                                	echo '<tr>
                                        <td class="libelle">' . $detail->getLibelle(ESC_RAW) . '</td>
                                      </tr>
                                      <tr class="contenu">
                                        <td align="center" >Pas de contrat défini pour ce produit.<br/>Merci de contacter votre interpro</td>
                                      </tr>';
                            		
                            	}
                            } else {
                                include_partial('addContrat', array('detail' => $detail));
                            }
                            if (isset($forms[$detail->getIdentifiantHTML()])) {
                                foreach ($forms[$detail->getIdentifiantHTML()] as $form) {
                                    include_partial('itemContrat', array('form' => $form));
                                }
                            }
                            ?>
                            </table>
                            <?php
                        }
                        ?>
                    
                <?php endif; ?>
            <br />
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_recap_redirect', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php if (!count($noContrats)) : ?>
                    <form action="<?php echo url_for('drm_vrac', $drm) ?>" method="post">
                        <button type="submit" class="btn_suiv"><span>Suivant</span></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</section>