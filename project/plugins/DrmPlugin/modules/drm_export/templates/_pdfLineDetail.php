<?php foreach($certification_config->detail->get($hash) as $item): ?>
	<?php include_partial('drm_export/pdfLineFloat', array('libelle' => $item->getLibelle(), 
													   	   'colonnes' => $colonnes, 
														   'hash' => $hash.'/'.$item->getKey(),
														   'cssclass_libelle' => 'detail')) ?>
<?php endforeach; ?>