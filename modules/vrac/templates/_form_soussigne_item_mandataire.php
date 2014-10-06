<?php if(isset($form['mandataire_exist'])): ?>
<div class="contenu_onglet bloc_condition" data-condition-cible="#bloc_mandataire">
    <?php echo $form['mandataire_exist']->renderError() ?>
    <?php echo $form['mandataire_exist']->renderLabel() ?>
    <?php echo $form['mandataire_exist']->render() ?>
</div>
<?php endif; ?>

<div id="bloc_mandataire" class="vrac_mandataire <?php if (isset($form['mandataire_exist'])): ?>bloc_conditionner<?php endif; ?>" data-condition-value="1">
    <h1>Courtier <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_informations" title="Message aide"></a></h1>
    <?php if(!$form->etablissementIsCourtier()): ?>
    <div>
        <h2>Sélectionner un courtier :</h2>
        <div class="section_label_strong" id="listener_mandataire_choice">
            <?php echo $form['mandataire_identifiant']->renderError() ?>
            <label for="">Nom : <a class="msg_aide" href="" data-msg="help_popup_vrac_etablissement_manquant" title="Message aide"></a></label>
            <?php echo $form['mandataire_identifiant']->render(array('data-infos-container' => '#bloc_mandataire .etablissement_informations')) ?>
        </div>
        <div  class="bloc_form etablissement_informations"> 
            <?php include_partial('form_mandataire', array('form' => $form['mandataire'])); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($form->etablissementIsCourtier()): ?>
    <div class="soussigne_vous">
        <h2>Vous êtes le courtier</h2>
        <div class="section_label_strong" id="listener_mandataire_choice">
            <label for="">Nom :</label>
            <?php echo $form->getEtablissement()->getNom(); ?>
        </div>
        <div  class="bloc_form etablissement_informations"> 
            <?php include_partial('form_mandataire_defaut', array('form' => $form['mandataire'], 'etablissement' => $form->getEtablissement())); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
$("#<?php echo (isset($form['mandataire_exist']))? $form['mandataire_exist']->renderId() : ''; ?>_0").click(function() {
	var select = $("#listener_mandataire_choice select");
	var input = $("#listener_mandataire_choice input");
	var btn = $("#listener_mandataire_choice button");
	if (select.children("option").length == 1) {
		select.prepend($( "<option value=\"\"></option>" ));
	}
	select.val("");
	input.val("");
	btn.hide();	
});
</script>