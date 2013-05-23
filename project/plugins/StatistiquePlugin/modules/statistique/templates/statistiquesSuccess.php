<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'drm')); ?>
<section id="contenu">
	<section id="principal">
		<div class="clearfix" id="application_dr">
		
    		<h1><strong><?php echo $statistiquesConfig['title'] ?></strong></h1>
    		
    		<div class="contenu clearfix">
	        	<?php include_partial('formStatistiqueFilter', array('type' => $type, 'form' => $form)) ?>
	        </div>
	        
    		<h1>Résultat</h1>
    		
    		<?php include_partial('resultStatistiqueFilter', array('statistiquesConfig' => $statistiquesConfig, 'hits' => $hits)) ?>
    		
    		<?php if ($nbPage > 1): ?>
    		<?php include_partial('paginationStatistiqueFilter', array('type' => $type, 'nbPage' => $nbPage, 'queryName' => $form['query']->renderName(), 'query' => $query, 'page' => $page)) ?>
    		<?php endif; ?>
    		
    		<br /><br />
    		
    		<h1>Statistiques</h1>
    		
    		<?php include_partial('facetsStatistiqueFilter', array('facets' => $facets)) ?>
    	</div>
	</section>
</section>
<style type="text/css">
table th, table td {
	padding : 0 10px;
	text-align: left;
}
</style>