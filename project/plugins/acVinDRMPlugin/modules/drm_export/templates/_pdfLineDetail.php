<?php $i = 'a'; foreach($stocks as $key => $item): ?>
	<?php if(!$drm->hasMouvements($hash.'/'.$key)) {continue;} ?>
	<?php include_partial('drm_export/pdfLineFloat', array('libelle' => $item, 
													   	   'colonnes' => $colonnes,  
													   	   'counter' => $counter.$i, 
													   	   'unite' => 'hl',
														   'hash' => $hash.'/'.$key,
														   'cssclass_value' => 'detail',
														   'cssclass_libelle' => 'detail')) ?>
<?php $i++; endforeach; ?>