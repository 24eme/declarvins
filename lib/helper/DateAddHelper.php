<?php

function getIsoDateFinDeMoisISO($date,$nb_mois) 
{
    preg_match('/^([0-9]{4})/([0-9]{2})/([0-9]{2})$/', $date, $matches);
    $annee = $matches[1];
    $mois = $matches[2];
    $lastdaymonth = mktime(0,0,0,$mois+$nb_mois,0,$annee);
    return date('Y-m-d', $lastdaymonth);
}