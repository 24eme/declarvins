<?php

function noeudXml($produit, $noeud, &$xml, $exceptions = array()) {
	foreach ($noeud as $key => $children) {
		if (!is_numeric($key)) {
			$xml .= "<$key>";
			$xml .= noeudXml($produit, $children, $xml, $exceptions);
			$xml .= "</$key>";
		} else {
			$val = $produit->getTotalVolume($noeud);
			if ($val) {
				return (in_array($noeud->getKey(), $exceptions))? $val : sprintf("%01.02f", $val);
			} else {
				return (in_array($noeud->getKey(), array('stock-debut-periode', 'stock-fin-periode')))? 0 : null;
			}
		}
	}
}

function formatXml($xml, $level = 0) {
	while (preg_match("/\<[a-zA-Z0-9\-\_]*\>\<\/[a-zA-Z0-9\-\_]*\>/", $xml)) {
		$xml = preg_replace("/\<[a-zA-Z0-9\-\_]*\>\<\/[a-zA-Z0-9\-\_]*\>/", "", $xml);
	}
	$xml = preg_replace("/\<([a-zA-Z0-9\-\_]*)\>\<([a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level + 1)."<$2>", $xml);
	$xml = preg_replace("/\<(\/[a-zA-Z0-9\-\_]*)\>\<([a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level)."<$2>", $xml);
	$xml = preg_replace("/\<(\/[a-zA-Z0-9\-\_]*)\>\<(\/[a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level)."<$2>", $xml);
	$xml = preg_replace("/\<(\/[a-zA-Z0-9\-\_]*)\>\<(\/[a-zA-Z0-9\-\_]*)\>/", str_repeat("\t", 1)."<$1>\n".str_repeat("\t", $level)."<$2>", $xml);
	$xml = preg_replace("/\<([a-zA-Z0-9\-\_]*)\>\<([a-zA-Z0-9\-\_]*)\>/", "<$1>\n".str_repeat("\t", $level + 2)."<$2>", $xml);
	return str_repeat("\t", $level).$xml."\n";
}

function drm2CrdCiel($drm) {
	$crds = array();
	foreach ($drm->crds as $crd) {
		$crds[$crd->categorie->code.$crd->type->code][] = $crd;
	}
	return $crds;
}
