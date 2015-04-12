<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'bilan_drm')); ?>
<section id="contenu">
    <section id="principal">
        <div class="clearfix" id="application_dr">
            <h1>Etat des DRM saisies</h1>
            <p>
                <?php foreach (array_values(DRMClient::getAllLibellesStatusBilan()) as $num => $libelle): ?>
                    <?php if ($num == 1): ?><font color="green"><?php endif; ?>
                    <strong><?php echo $num; ?></strong> : <?php echo $libelle; ?>
                    <?php if ($num == 1): ?></font><?php endif; ?>
                    <br />
                <?php endforeach; ?>
            </p>
            <br />
            <div class="contenu clearfix">
                <?php include_partial('formCampagne', array('form' => $formCampagne)) ?>
            </div>
            <br />
            <?php if ($statistiquesBilan): ?>
                <div class="tableau_ajouts_liquidations">
                    <a href="<?php echo url_for('statistiques_bilan_drm_csv', array('interpro' => $interpro->get('_id'), 'campagne' => $campagne)) ?>">CSV</a>&nbsp;|&nbsp;
                                        <a class="btn_popup" data-popup-config="configForm" data-popup="#popup_select_periode" href="">CSV N-1</a>
                    <div class="popup_contenu" style="display: none;">
                        <form  class="popup_form" id="popup_select_periode" action="#" method="post">
                            <div class="ligne_form">
                                <label for="select_periode">Récupérer par période :</label>
                                <select id="select_periode" name="select_periode">
                                    <?php foreach ($statistiquesBilan->getPeriodes() as $periode): ?>
                                    <option value="<?php echo url_for('statistiques_drm_manquantes_csv', array('interpro' => $interpro->get('_id'), 'campagne' => $campagne, 'periode' => $periode)); ?>"><?php echo $periode; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="ligne_form_btn">
                                <button class="btn_annuler btn_fermer" type="reset" name="annuler">Annuler</button>
                                <button class="btn_valider" type="submit" name="valider">Valider</button>
                            </div>
                        </form>
                        <script type="text/javascript">
                            $('#popup_select_periode').submit(function() {
                                document.location.href = $('#select_periode').val();
                                return false;
                            });
                        </script>
                    </div>

                    <table class="tableau_recap">
                        <thead>
                            <tr>
                                <th style="padding: 0 5px;"><strong>Etablissements</strong></th>
                                <?php foreach ($statistiquesBilan->getPeriodes() as $periode): ?>
                                    <th style="padding: 0;"><strong><?php echo $periode ?></strong></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $statusArray = array_keys(DRMClient::getAllLibellesStatusBilan());
                            foreach ($statistiquesBilan->getBilans() as $bilanOperateur):
                                ?>
                                <tr>
                                    <td style="padding: 0 5px;">
                                        <?php echo $bilanOperateur->etablissement->raison_sociale; ?> <?php echo $bilanOperateur->etablissement->nom; ?> (<?php echo $bilanOperateur->identifiant ?>)<br />
                                        Siret : <?php echo $bilanOperateur->etablissement->siret; ?><br />
                                        Cvi : <?php echo  $bilanOperateur->etablissement->cvi; ?><br />
                                        Num. Accises : <?php echo $bilanOperateur->etablissement->no_accises; ?><br />
                                        <?php echo $bilanOperateur->etablissement->siege->adresse; ?><br />
                                        <?php echo $bilanOperateur->etablissement->siege->code_postal; ?> <?php $bilanOperateur->etablissement->siege->commune; ?><br />
                                        <?php echo $bilanOperateur->etablissement->siege->pays; ?><br />
                                        @ : <?php echo $bilanOperateur->etablissement->email; ?><br />
                                        Tèl : <?php echo $bilanOperateur->etablissement->telephone; ?> Fax :<?php echo $bilanOperateur->etablissement->fax; ?><br />
                                        Service douane : <?php echo $bilanOperateur->etablissement->service_douane ?><br />
                                        Statut : <?php echo $bilanOperateur->etablissement->statut ?><br />
                                    </td>
                                    <?php
                                    foreach ($statistiquesBilan->getPeriodes() as $periode):
                                        ?>
                                        <td style="padding: 0;">
                                            <strong>
                                             <?php echo (!isset($bilanOperateur->periodes[$periode]) || is_null($bilanOperateur->periodes[$periode]))? array_search(DRMClient::DRM_STATUS_BILAN_STOCK_EPUISE, $statusArray) : array_search($bilanOperateur->periodes[$periode]->statut, $statusArray); ?>
                                            </strong>
                                        </td>
                                        <?php
                                    endforeach;
                                    ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</section>