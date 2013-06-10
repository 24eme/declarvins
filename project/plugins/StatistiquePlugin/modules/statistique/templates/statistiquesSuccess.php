<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'drm')); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
		
    		<h1><strong><?php echo $statistiquesConfig['title'] ?></strong></h1>
    		<h1>Filtres</h1>
    		<div class="contenu clearfix">
	        	<?php include_partial('formStatistiqueFilter', array('type' => $type, 'form' => $form)) ?>
	        </div>
	        
    		<h1><?php echo $nbHits ?> résultat<?php if ($nbHits > 1): ?>s<?php endif; ?></h1>
    		
    		<?php if ($nbHits > 0): ?>
    			<?php include_partial('resultStatistiqueFilter', array('hashProduitFilter' => $hashProduitFilter, 'statistiquesConfig' => $statistiquesConfig, 'hits' => $hits)) ?>
    		<?php else: ?>
    			<p>Aucun résultat pour la recherche</p>
    		<?php endif; ?>
    		
    		<?php if ($nbPage > 1): ?>
    			<?php include_partial('paginationStatistiqueFilter', array('type' => $type, 'nbPage' => $nbPage, 'queryName' => 'query', 'query' => $query, 'page' => $page)) ?>
    		<?php endif; ?>
    		
    		<?php if ($nbHits > 0): ?>
    			<h1>Statistiques</h1>
    			<?php include_partial('facetsStatistiqueFilter', array('nbDoc' => $nbHits, 'facets' => $facets)) ?>
    		<?php endif; ?>
    		
    	</div>
	</section>
</section>
<style type="text/css">
table th, table td {
	padding : 0 10px;
	text-align: left;
}
</style>