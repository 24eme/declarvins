<div id="statut_declaration">
	<nav id="declaration_etapes">
		<ol>
			<?php getDeclarationEtapes($declaration_etape); ?>
		</ol>
	</nav>
	
	<div id="etat_avancement">
		<p>Vous avez saisi <strong><?php echo $declaration_avancement; ?><span>%</span></strong></p>
		<div id="barre_avancement">
			<div style="width: <?php echo $declaration_avancement; ?>%"></div>
		</div>
	</div>
</div>