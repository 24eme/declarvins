<style>
span, label {
	display: inline-block;
	width: 170px;
}
.btn_valider {
    background-color: #820608;
    background-position: right -52px;
    background-repeat: no-repeat;
    border: 1px solid #A12929;
    color: #FFFFFF;
    display: inline-block;
    padding: 0 23px 0 15px;
    text-transform: uppercase;
    height: 20px;
}
input[type="text"] {
	background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #D3D2CD;
    border-radius: 2px 2px 2px 2px;
    font-size: 100%;
    position: relative;
    vertical-align: middle;
    height: 18px;
    padding: 0 4px;
    width: 220px;
}
</style>
<?php include_partial('global/navBack', array('active' => 'etablissement')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<div id="principal">
	<div id="contenu_onglet">
	<div class="notice">
		<h2>Raccourcis clavier :</h2>
		<ul>
			<li><kbd>Ctrl</kbd> + <kbd>Gauche</kbd> : Aller à la colonne de
				gauche</li>
			<li><kbd>Ctrl</kbd> + <kbd>Droite</kbd> : Aller à la colonne de
				droite</li>
			<li><kbd>Ctrl</kbd> + <kbd>M</kbd> : Commencer la saisie de la
				colonne courante</li>
			<li><kbd>Ctrl</kbd> + <kbd>Z</kbd> : Réinitialiser les valeurs de la
				colonne courante</li>
			<li><kbd>Ctrl</kbd> + <kbd>Entrée</kbd> : Valider la colonne courante</li>
		</ul>
	</div>
	</div>
	</div>
	<h1>Connexion</h1>
    <div id="mon_compte">
        <?php include_partial('admin/etablissement_login_form', array('form' => $form))?>
    </div>
</div>
</section>