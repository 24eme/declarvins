<?php

function getCvoLabels($label)
{
   $cvo_nature = array(VracClient::CVO_NATURE_MARCHE_DEFINITIF => 'Marché définitif',
                       VracClient::CVO_NATURE_COMPENSATION => 'Compensation',
                       VracClient::CVO_NATURE_NON_FINANCIERE => 'Non financière',
                       VracClient::CVO_NATURE_VINAIGRERIE => 'Vinaigrerie');
   return $cvo_nature[$label];
}

function dateCampagneViticolePresent()
{
    $date = date('mY');
    $mois = substr($date, 0,2);
    $annee = substr($date, 2,6);
    $campagne = ($mois<8)? ($annee-1).'/'.$annee : $annee.'/'.($annee+1);
    return $campagne;
}

function dateCampagneViticole($date)
{
    $date_exploded = explode("/", $date);
    $mois = $date_exploded[1];
    $annee = $date_exploded[2];
    $campagne = ($mois<8)? ($annee-1).'/'.$annee : $annee.'/'.($annee+1);
    return $campagne;
}
 
function isARechercheParam($actif,$label)
{
    return $actif==$label;
}

function statusColor($status)
{

    if(is_null($status)) return '';

    switch ($status)
    {
        case VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION:
            return 'statut_att_annule';
        case VracClient::STATUS_CONTRAT_ANNULE:
            return 'statut_annule';
        case VracClient::STATUS_CONTRAT_SOLDE:
            return 'statut_solde';
        case VracClient::STATUS_CONTRAT_NONSOLDE:
            return 'statut_non-solde';
        case VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION:
            return 'statut_attente-validation';
        default :
            return '';
    }
}

function statusLibelle($status, $pluriannuel = null)
{

    if(is_null($status)) return '';

    if ($status == VracClient::STATUS_CONTRAT_SOLDE && $pluriannuel) return 'Validé';

    switch ($status)
    {
        case VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION:
            return 'En attente d\'annulation';
        case VracClient::STATUS_CONTRAT_ANNULE:
            return 'Annulé';
        case VracClient::STATUS_CONTRAT_SOLDE:
            return 'Soldé';
        case VracClient::STATUS_CONTRAT_NONSOLDE:
            return 'Non soldé';
        case VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION:
            return 'En attente de validation';
        default :
            return $status;
    }
}

function showTypeFromLabel($type)
{
    switch ($type)
        {
            case 'vin_vrac': return 'vin vrac';
            case 'vin_bouteille': return 'vin conditionné';
            default: return $type;
        }
}

function getTypeIcon($type)
{
    switch ($type) {
        case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE :
            return 'icon-bouteille';
        case VracClient::TYPE_TRANSACTION_VIN_VRAC :
            return 'icon-vrac';
        case VracClient::TYPE_TRANSACTION_MOUTS :
            return 'icon-mouts';
        case VracClient::TYPE_TRANSACTION_RAISINS :
            return 'icon-raisins';
        default:
            return '';
    }
}


function typeProduit($type)
{
    switch ($type) {
        case VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE :
            return 'Btl';
        case VracClient::TYPE_TRANSACTION_VIN_VRAC :
            return 'V';
        case VracClient::TYPE_TRANSACTION_MOUTS :
            return 'M';
        case VracClient::TYPE_TRANSACTION_RAISINS :
            return 'R';
    }
    return '';
}   