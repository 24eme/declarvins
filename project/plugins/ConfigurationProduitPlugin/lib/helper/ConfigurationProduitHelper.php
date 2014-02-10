<?php

function printLibelleNode($node)
{
   if ($node == 'certification') {
   	return 'catégorie';
   }
   if ($node == 'appellation') {
   	return 'dénomination';
   }
   return $node;
}