<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('declarvin', 'dev', true);
sfContext::createInstance($configuration)->dispatch();

unlink(sfConfig::get('sf_web_dir').'/css/compile.css');
$lessFile = sfConfig::get('sf_web_dir').'/css/includes.less';
$cssFile = sfConfig::get('sf_web_dir').'/css/compile.css';
lessc::ccompile($lessFile, $cssFile);
