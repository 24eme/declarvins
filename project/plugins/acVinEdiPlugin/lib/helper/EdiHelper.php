<?php

function noeudXml($noeud, &$xml, $level = 0) {
	foreach ($noeud as $key => $children) {
		if (!is_numeric($key)) {
			$xml .= "\n";
			$xml .= str_repeat("\t", $level)."<$key>";
			$xml .= noeudXml($children, $xml, $level + 1);
			$xml .= "</$key>";
		} else {
			return 'nil';
		}
	}
	$xml .= "\n";
}