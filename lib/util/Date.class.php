<?php

class Date {

	public static function diff($date1, $date2, $retour) {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
	    $diff = $date1->diff($date2);
	    return $diff->{$retour};
  	}

}