<?php
foreach($csv->getRawValue()->getCsv() as $line) 
{
  echo implode(';', $line)."\n";
}