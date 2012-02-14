<?php
	require_once('lessc.inc.php');
	require_once('constantes.php');
	require_once('fonctions.php');
	
	$less_css = CSS_PATH.'includes.less';
	
	try
	{
		$less = new lessc($less_css);
		file_put_contents(CSS_PATH.'compile.css', $less->parse());
	}
	catch (exception $ex)
	{
		exit('lessc fatal error:<br />'.$ex->getMessage());
	}
	
	$js_spec = array("includes.js");
	$js_spec_min = array("includes.min.js");
?>