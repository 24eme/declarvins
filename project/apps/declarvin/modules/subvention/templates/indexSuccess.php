<style>
ul {
    margin-bottom: 0px;
}
a:hover, a:active {
    color: inherit;
    text-decoration: underline;
}
a, a:link, a:visited, .link {
    color: inherit;
}
label {
    font-weight: normal;
    margin-bottom: 0px;
}
h1 {
    height: inherit;
}
</style>
<?php include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => 'subvention')); ?>
<section id="contenu">
<div class="clearfix" id="application_dr">
	<h1>Connexion</h1>
    <div id="mon_compte" style="margin-bottom: 15px;">
        <?php include_component('admin', 'etablissement_login_form', array('interpro' => InterproClient::getInstance()->find('INTERPRO-IR'), 'route' => '@etablissement_login_subvention'))?>
    </div>
    <?php require_once sfConfig::get('sf_plugins_dir').'/acVinSubventionPlugin/modules/subvention/templates/indexSuccess.php';  ?>
</div>
</section>