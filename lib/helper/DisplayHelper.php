<?php

function display_field($object,$fieldName)
{
    if(is_null($object)){ echo ''; return;}
    echo (!is_null($object->$fieldName))? $object->$fieldName : '';
}