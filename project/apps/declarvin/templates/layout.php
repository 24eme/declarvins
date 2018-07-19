<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>



        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="author" content="Actualys" />
        <meta name="Description" content="" /> 
        <meta name="Keywords" content="" />
        <meta name="robots" content="noindex,nofollow" />
        <meta name="Content-Language" content="fr-FR" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="copyright" content="DéclarVins - 2011" />

        <style type="text/css">
            .rectifier {
                outline: 1px dotted #ff0000 !important;
            }
            .versionner {
                outline: 1px dotted #ff0000 !important;
            }
        </style>

        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
    </head>

    <body style="background-color: #C44C51;">
    
    	
		<?php include_partial('global/konami'); ?>

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
            <div id="global_content">
                <?php echo $sf_content ?>

                <?php include_partial('global/footer'); ?>
            </div>

        </div>
        <!-- fin #global -->
        <?php include_partial('global/initMessageAide') ?>
        <?php include_partial('global/ajaxNotification') ?> 
        <!-- ####### A REPRENDRE ABSOLUMENT ####### -->
        <!--[if lte IE 9 ]> </div> <![endif]-->
        <!-- ####### A REPRENDRE ABSOLUMENT ####### -->
        <script type="text/javascript">var jsPath = "/js/";</script>
        <script type="text/javascript" src="/js/includes.js?20170712"></script>
        <script type="text/javascript" src="/js/lib/select2.min.js"></script>
    </body>

</html>
