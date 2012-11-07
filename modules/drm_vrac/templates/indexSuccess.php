<?php include_component('global', 'navTop', array('active' => 'drm')); ?>

<section id="contenu" class="drm_vracs">

    <?php include_partial('drm/header', array('drm' => $drm)); ?>
    <?php include_component('drm', 'etapes', array('drm' => $drm, 'etape' => 'vrac', 'pourcentage' => '30')); ?>

    <section id="principal">
    	<p>Vous avez indiqué des sorties vrac, veuillez indiquer ci-dessous les contrats interprofessionnels ainsi que les volumes concernés.<br />Si le contrat auquel vous souhaitez faire référence n'est pas présent, veuillez vous rendre sur l'interface de saisie des <a href="<?php echo url_for('vrac_etablissement', array('sf_subject' => $etablissement)) ?>">contrats interprofessionnels</a></p>
    	<br /><br />
    	
        <div id="application_dr">
            <form action="<?php echo url_for('drm_vrac', $drm) ?>" method="post">
            <?php echo $form->renderHiddenFields(); ?>
            <?php echo $form->renderGlobalErrors(); ?>
			<?php 
				$nbDetailSansContrat = 0;
				foreach ($form as $detailHash => $formDetail):
				if ($drm->exist($detailHash)):
					$detail = $drm->get($detailHash);
			?>
            <table width="100%" class="contrat_vracs" id="contrats<?php echo $detail->getIdentifiantHTML() ?>">
            	<tbody>
	            	<?php include_partial('add_contrat', array('detail' => $detail, 'hasContrat' => count($detail->getContratsVrac()))); ?>
	            	<?php 
	            		if (!count($detail->getContratsVrac())): 
	            			$nbDetailSansContrat++;
	            	?>
					<tr class="contenu">
						<td colspan="3">Pas de contrat défini pour ce produit. Merci de contacter votre interpro</td>
					</tr>
					<?php else: ?>
	                <?php foreach ($formDetail as $formContrats): ?>
	                <?php foreach ($formContrats as $k => $formContrat): ?>
	                <?php include_partial('form_contrat_item', array('form' => $formContrat)); ?>
	                <?php endforeach; ?>
	                <?php endforeach; ?>
	                <?php endif; ?>
                </tbody>
            </table>
            <script id="template_form_detail_contrats_item<?php echo $detail->getIdentifiantHTML() ?>" class="template_form" type="text/x-jquery-tmpl">
    			<?php echo include_partial('form_contrat_item', array('form' => $form->getFormTemplateDetailContrats($detail->getHash()))); ?>
			</script>
			<?php
				endif; 
				endforeach;
			?>
            
            <br />
            
            <div id="btn_etape_dr">
                <a href="<?php echo url_for('drm_recap_redirect', $drm) ?>" class="btn_prec">
                    <span>Précédent</span>
                </a>
                <?php if ($nbDetailSansContrat == 0) : ?>
                	<button type="submit" class="btn_suiv"><span>Suivant</span></button>
                <?php endif; ?>
            </div>

            <div class="ligne_btn" style="margin-top: 30px;">
                <a href="<?php echo url_for('drm_delete', $drm) ?>" class="annuler_saisie btn_remise"><span>annuler la saisie</span></a>
            </div>

            </form>
        </div>
    </section>
</section>
<script type="text/javascript">
$(document).ready(function () {
		$(".drm_vrac_contrats").combobox(); 
});
</script>
