<section id="contenu">
	<div id="creation_compte" style="width:100%;">
	    <!-- #application_dr -->
	    <div class="clearfix" id="application_dr">
	
	        <!-- #nouvelle_declaration -->
	        <div id="nouvelle_declaration">
	            <form action="<?php echo url_for('@drm_login') ?>" method="post" id="principal">
	            <h3 class="titre_section">Connexion</h3>
	            <div class="contenu_section">
	                <p class="intro">Pour vous connecter, merci d'indiquer le login :</p><br /><br />
	                <?php echo $form->renderHiddenFields(); ?>
	                <?php echo $form->renderGlobalErrors(); ?>
	                <div class="ligne_form ligne_form_label">
	                    <?php echo $form['login']->renderError() ?>
	                    <?php echo $form['login']->renderLabel() ?>
	                    <?php echo $form['login']->render() ?>
	                </div>
	                <div class="ligne_form ligne_btn">
	                    <input type="submit" value="Valider" name="boutons[valider]" class="btn">
	                </div>
	            </div>
	            </form>
	        </div>
	    </div>
	</div>
</section>