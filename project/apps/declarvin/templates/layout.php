<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
	<?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    
    <link rel="stylesheet" type="text/css" href="http://webfonts.fontslive.com/css/47bf5cee-2e68-410c-a2b9-19ca7c9808f2.css" media="screen" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
</head>

<body>

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 6 ]> <div class="ie6 ielt7 ielt8 ielt9"> <![endif]-->
<!--[if IE 7 ]> <div class="ie7 ielt8 ielt9"> <![endif]-->
<!--[if IE 8 ]> <div class="ie8 ielt9"> <![endif]-->
<!--[if IE 9 ]> <div class="ie9"> <![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->

	<!-- #global -->
	<div id="global">
            
                <?php include_partial('global/header'); ?>
                
		<!-- fin #header -->
		<?php echo $sf_content ?>
		
                <?php include_partial('global/footer'); ?>
	
	</div>
	<!-- fin #global -->

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 9 ]> </div> <![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
	<script type="text/javascript">var jsPath = "/js/";</script>
        <script type="text/javascript" src="/js/includes.js"></script>
</body>

</html>
