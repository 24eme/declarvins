<?php include_component('global', 'navBack', array('active' => 'statistiques', 'subactive' => 'vrac')); ?>
<section id="contenu" class="statistiques">
	<section id="principal">
		<div class="clearfix" id="application_dr">
			
    		<h1><strong><?php echo $statistiquesConfig['title'] ?></strong></h1>

    		<div class="contenu clearfix">
	        	<?php include_partial($form->getFormTemplate(), array('type' => 'vrac', 'form' => $form)) ?>
	        </div>
	        
	        <div style="width: 100%; display: inline-block;">
			<?php if($nbHits > 0): ?>
				
				<div style="float: left; width: 300px;">
    			<?php include_partial('facetsStatistiqueFilter', array('nbDoc' => $nbHits, 'facets' => $facets, 'configFacets' => $statistiquesConfig['facets'])) ?>
				</div>
				<div style="float: left; width: 600px;">
    			<?php if (isset($chartConfig)): ?>
					<?php include_partial('facetsChart', array('chartConfig' => $chartConfig, 'title' => 'Saisies des contrats interprofessionnels')) ?>
				<?php endif; ?>
				</div>
			<?php endif; ?>
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