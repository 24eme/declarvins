<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
	<title>
		<?php if($titre_rub != $titre_page) echo $titre_page." | "; ?>
		<?php echo $titre_rub." | "; ?>
		<?php echo NOM_SITE; ?>
	</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Actualys" />
	<meta name="Description" content="" /> 
	<meta name="Keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta name="Content-Language" content="fr-FR" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="copyright" content="<?php echo NOM_SITE; ?> - 2011" />
	
	<link rel="stylesheet" type="text/css" href="http://webfonts.fontslive.com/css/4145075a-3116-42db-9643-0f49fcd9e55e.css" media="screen" />
	
	<!-- Fichier compilé par LESS -->
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH; ?>compile.css" media="screen" />
	
	<script type="text/javascript">var jsPath = "<?php echo JS_PATH; ?>";</script>
	<script type="text/javascript">var ajaxPath = "../ajax.php";</script>
	<script type="text/javascript" src="<?php echo JS_PATH; ?>lib/modernizr-2.js"></script>
</head>

<body>

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 6 ]> <div class="ie6 ielt7 ielt8 ielt9"> <![endif]-->
<!--[if IE 7 ]> <div class="ie7 ielt8 ielt9"> <![endif]-->
<!--[if IE 8 ]> <div class="ie8 ielt9"> <![endif]-->
<!--[if IE 9 ]> <div class="ie9"> <![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 6 ]>
<div id="message_ie6">
	<div class="gabarit">
		<p><strong>Vous utilisez un navigateur obsolète depuis près de 10 ans !</strong> Il est possible que l'affichage du site soit fortement altéré par l'utilisation de celui-ci.</p>
	</div>
</div>
<![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->

	<!-- #global -->
	<div id="global">
	
		<!-- #header -->
		<header id="header">
			<nav>
				<ul>
					<li><a href="#">Contact</a></li>
					<li><a href="#">FAQ</a></li>
					<li class="profil"><a href="#">Mon profil</a></li>
				</ul>
			</nav>
			
			<div id="logo">
				<h1><a href="#" title="<?php echo NOM_SITE; ?> - Retour à l'accueil"><img src="../images/visuels/logo_declarvins.png" alt="<?php echo NOM_SITE; ?>" /></a></h1>
				<p>La plateforme déclarative des vins du Rhône, de Provence et du Sud Est</p>
			</div>

			<p class="deconnexion"><a href="#">Se deconnecter</a></p>
		</header>
		<!-- fin #header -->