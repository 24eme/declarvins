<?php

function sprintFloat($float, $format = null)
{
	if (is_null($float))
	return null;
	if (!$format) {
		$tab = explode('.', str_replace(',', '.', $float));
		$nb = (isset($tab[1]))? strlen($tab[1]) : 2;
		if ($nb > 5) {
			$nb = 5;
		}
		if ($nb < 2) {
			$nb = 2;
		}
		$format = "%01.0".$nb."f";
	}
	return sprintf($format, $float);
}

function sprintFloatFr($float, $format = "%01.02f")
{

  return preg_replace('/\./', ',', sprintFloat($float, $format));
}

function echoFloat($float) 
{
  echo sprintFloat($float);
}

function echoLongFloat($float) 
{
  echo sprintFloat($float, "%01.05f");
}

function echoLongFloatFr($float)
{
  echo sprintFloatFr($float, "%01.05f");
}

function echoFloatFr($float)
{
  echo sprintFloatFr($float);
}

function echoSignedFloat($float) 
{
  echo ($float>0)? '+'.sprintFloat($float) : sprintFloat($float);
}

function echoArialFloat($float) {
    echo number_format($float, 2, '.', ' ');
}

function getArialFloat($float) {
    return number_format($float, 2, '.', ' ');
}