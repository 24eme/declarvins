<?php

function display_field($object, $fieldName) {
    if (is_null($object)) {
        echo '';
        return;
    }
    echo (!is_null($object->$fieldName)) ? $object->$fieldName : '';
}

function display_latex_string($string, $sep = '', $limit = null) {
    $disp = "";
    $disp = str_replace("&#039;", "'", $string);
    $disp = str_replace("&amp;", "\&", $disp);
    
    if (!$limit && $sep == '')
        return $disp;
    
    if ($sep != '')
        $disp = str_replace($sep, "\\\\", $disp);
    $len = strlen($disp);
    if ($limit!=null && $len > $limit) {
        $d = substr($disp, 0, $limit);
        $pos = strrpos($d, ' ');        
        if ($pos !== FALSE) {
            $disp = substr($d, 0, $pos) . " \\\\ " . substr($disp, $pos, $len);
        }
    }  
    return $disp;
}