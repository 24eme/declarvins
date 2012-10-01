<?php

function sprintFloat($float) 
{
	if (is_null($float))
		return null;
  return sprintf("%01.02f", round($float, 2));
}

function sprintFloatFr($float)
{

  return preg_replace('/\./', ',', sprintFloat($float));
}

function echoFloat($float) 
{
  echo sprintFloat($float);
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