<?php

function sprintFloat($float) 
{
  return sprintf("%01.02f", round($float, 2));

}

function echoFloat($float) 
{
  echo sprintFloat($float);
}

function echoFloatFr($float)
{
  echo preg_replace('/\./', ',', sprintFloat($float));

}