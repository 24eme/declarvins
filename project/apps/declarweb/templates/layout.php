<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
  </head>
  <body id="declaration_recolte">
	<!-- #global -->
	<div id="global">
		<?php include_partial('global/errorFlash') ?>
		<div id="contenu">
			<?php echo $sf_content ?>
		</div>
	</div>
    <!-- fin #global -->
    <?php include_javascripts() ?>
  </body>
</html>
