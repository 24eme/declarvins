<?php
//require_once 'maintenance.php';exit;

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('declarvin', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
