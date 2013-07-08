<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'vrac')); ?>
<section id="contenu" class="statistiques">
	<section id="principal">
		<div class="clearfix" id="application_dr">
			
			<!-- .options_recherche -->
			<?php include_partial('vracSearchOptions'); ?>
			<!-- fin .options_recherche -->
			
    		<h1><strong><?php echo $statistiquesConfig['title'] ?></strong></h1>

    		<div class="contenu clearfix">
				
				<ul class="liste_filtres">
					<li></li>
				</ul>
				
	        	<?php include_partial($form->getFormTemplate(), array('type' => 'vrac', 'form' => $form)) ?>
	        </div>
			
    		<h2><?php echo $nbHits ?> résultat<?php if ($nbHits > 1): ?>s<?php endif; ?></h2>
    		
    		<?php if ($nbHits > 0): ?>
    			<?php include_partial('resultVracStatistiqueFilter', array('produits' => $produits, 'statistiquesConfig' => $statistiquesConfig, 'hits' => $hits)) ?>
    		<?php else: ?>
    			<p>Aucun résultat pour la recherche</p>
    		<?php endif; ?>
    		
    		<?php if ($nbPage > 1): ?>
    			<?php include_partial('paginationStatistiqueFilter', array('type' => 'vrac', 'nbPage' => $nbPage, 'page' => $page)) ?>
    		<?php endif; ?>
    		
    		<?php if ($nbHits > 0): ?>
    			<h2>Statistiques</h2>
    			<?php include_partial('facetsStatistiqueFilter', array('nbDoc' => $nbHits, 'facets' => $facets, 'configFacets' => $statistiquesConfig['facets'])) ?>
    		<?php endif; ?>
    		
    	</div>
	</section>
</section>
<script type="text/javascript">
$(".pagination_link").click(function() {
	$("form").attr('action', $(this).attr('href'));
	$("form").submit();
	return false;
});
</script>
<style type="text/css">
table th, table td {
	padding : 0 10px;
	text-align: left;
}
</style>