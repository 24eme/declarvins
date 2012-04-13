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
<?php include_partial('global/navBack', array('active' => 'admin')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
    <h1>Connexion</h1>
    <div id="mon_compte">
        <?php include_partial('admin/form', array('form' => $formLogin))?>
    </div>
</div>
</section>