<?php

function produitLibelle($libelles, $libelles_labels = array(), $format = "%a% %l% %co% %ce% %m% %la%", $label_separator = ", ") {
	$format_index = array('%c%' => 0,
						  '%a%' => 1,
						  '%l%' => 2,
						  '%co%' => 3,
						  '%ce%' => 4,
						  '%m%' => 5);

	$libelle = $format;

	foreach($format_index as $key => $item) {
		$libelle = str_replace($key, $libelles[$item], $libelle);
	}

	$libelle = labelsLibelles($libelles_labels, $libelle, $label_separator);

	return $libelle;
}

function labelsLibelles($libelles, $format = "%la%", $label_separator = ", ") {

	return str_replace("%la%", implode($label_separator, $libelles), $format);
}

function produitLibelleFormDetail($detail, $format = "%a% %l% %co% %ce% %m% %la%", $label_separator = ", ") {

	if ($detail instanceof sfOutputEscaperIteratorDecorator) {
		$detail = $detail->getRawValue();
	}

	if (!($detail instanceof DRMDetail)) {
		throw new sfRenderException("detail is not instanceof DRMDetail");
	}

	return produitLibelle($detail->getLibelles(), $detail->getLabelLibelles(), $format, $label_separator);
}