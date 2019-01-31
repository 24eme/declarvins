<?php

function echoHl($produit)
{
	echo getHl($produit);
}

function getHl($produit)
{
	return ($produit->getCertification()->getKey() == 'MP')? 'hlap' : 'hl';
}