<?php

function sprintFloat($float) 
{

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