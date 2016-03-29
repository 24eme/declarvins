<?php

function link_to_edi($name, $parameters)
{
	return sfProjectConfiguration::getActive()->generateEdiUrl($name, $parameters);
}