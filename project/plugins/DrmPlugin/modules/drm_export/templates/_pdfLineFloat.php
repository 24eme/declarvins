<?php include_partial('drm_export/pdfLine', array('libelle' => $libelle,
												  'colonnes' => $colonnes,
												  'hash' => $hash,
												  'format' => 'sprintFloatFr',
												  'format_params' => array(),
												  'cssclass_libelle' => isset($cssclass_libelle) ? $cssclass_libelle : null,
												  'cssclass_value' => isset($cssclass_value) ? $cssclass_value : 'number')) ?>