<?php

function display_field($object, $fieldName) {
    if (is_null($object)) {
        echo '';
        return;
    }
    echo (!is_null($object->$fieldName)) ? $object->$fieldName : '';
}

function get_field($object, $fieldName) {
    if (is_null($object)) {
        return '';
    }
    return (!is_null($object->$fieldName)) ? $object->$fieldName : '';
}

function escape_string_for_latex($string) {
    $disp = html_entity_decode($string);
    $disp = str_replace("&#039;", "'", $disp);
    $disp = str_replace("&amp;", "&", $disp);
    $disp = str_replace("&", "\&", $disp);
    $disp = str_replace("%", "\%", $disp);
    $disp = str_replace("<", "$<$", $disp);
    $disp = str_replace(">", "$>$", $disp);
    $disp = str_replace("_", "", $disp);
    return $disp;
}

function display_latex_string($string, $sep = '', $limit = null, $nb_max = null) {
    $disp = escape_string_for_latex($string);

    if (!$limit && $sep == '')
        return $disp;

    if ($sep)
        $disp = str_replace($sep, " \\\\ ", $disp);

    if($nb_max!=null && substr_count($disp, " \\\\ ") >= $nb_max) return $disp;

    $len = strlen($disp);

    if ($limit!=null && $len > $limit) {
        $d = substr($disp, 0, $limit);
        $pos = strrpos($d, ' ');
        if ($pos !== FALSE) {
            $disp = substr($d, 0, $pos) . "\\\\ " . substr($disp, $pos, $len);
        }
    }
    return $disp;
}

function display_latex_message_communication($string) {
     $longueur_msg = strlen($string);
    $msg = escape_string_for_latex($string);
        echo "\centering {".$msg."}";
        return;

}

function cut_latex_string($string, $limit) {
    $disp = escape_string_for_latex($string);

    $len = strlen($disp);
    if ($len > $limit) {
        $disp = substr($disp, 0, $limit-3).'...';
    }
    return $disp;
}

function enteteDs($ds,$date_echeance){
    if($ds->getEtablissement()->isNegociant())
        return 'Cet imprimé doit \^{e}tre obligatoirement rempli \underline{\textbf{avant le '.$date_echeance.'}} au plus\\\\tard par tous les négociants, \textbf{détenant des \underline{stocks de vins d\'appellation}}\\\\ \textbf{\underline{d\'origine}} (revendiqués et/ou agrées) \textbf{et quels que soient leurs lieux}\\\\ \textbf{d\'entreposage} selon la liste proposée ci-après conformément à l\'Accord\\\\ Interprofessionnel d\'InterLoire en vigueur.';
    else
        return 'Cet imprimé doit \^{e}tre obligatoirement rempli \textsl{\textbf{avant le '.$date_echeance.'}} au plus tard\\\\par tous les propriétaires, fermiers, métayers, groupements de producteurs\\\\ \textbf{détenant des \textsl{stocks de vins d\'appellation d\'origine}} (revendiqués et/ou agrées)\\\\ \textbf{et quels que soient leurs lieux d\'entreposage} selon la liste proposée ci-après\\\\conformément à l\'Accord Interprofessionnel d\'InterLoire en vigueur.';
}

function display_numero_tel($num) {
    $int = (int) filter_var($num, FILTER_SANITIZE_NUMBER_INT);
    if (strpos($int, '330') === 0) {
        $int = substr($int, 3);
    } elseif (strpos($int, '33') === 0) {
        $int = substr($int, 2);
    } elseif (strpos($int, '0') === 0) {
        $int = substr($int, 1);
    }
    return (strlen($int)==9)? wordwrap("0$int", 2, " ", 1) : $num;
}
