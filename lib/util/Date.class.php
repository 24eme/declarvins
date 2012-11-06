<?php

class Date {

	public static function diff($date1, $date2, $retour) {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
	    $diff = $date1->diff($date2);
	    return $diff->{$retour};
  	}

        public static function getIsoDateFinDeMoisISO($date,$nb_mois) 
        {
            preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $date, $matches);
            $annee = $matches[1];
            $mois = $matches[2];
            $lastdaymonth = mktime(0,0,0,$mois+$nb_mois+1,0,$annee);
            return date('Y-m-d', $lastdaymonth);
        }
        
        public static function getIsoDateFromFrenchDate($french_date) 
        {
            $matches = array();
            try{
            preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $french_date, $matches);
            $jours = $matches[1];
            $mois = $matches[2];
            $annee = $matches[3];
            }
             catch (Exception $e)
            {
                throw new sfException("The date $french_date cannot be transformed");
            }
            return date('Y-m-d',mktime(0,0,0,$mois,$jours,$annee));
        }
       
}