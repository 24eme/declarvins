<?php

class ConfigurationZoneRouting
{

    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) 
    {
		$r = $event->getSubject(); 
    }

}
