<?php header("Content-type: text/css"); ?>

@import 'toolbox.less';
@import 'vars.less';
@import 'fancybox.css';
@import 'global.less';
<?php
/* CSS spécifique à la page */
if($_GET['css_spec'])
{
	$css_spec = explode(';', $_GET['css_spec']);
	foreach($css_spec as $css) echo "@import '".$css."';";
}
?>