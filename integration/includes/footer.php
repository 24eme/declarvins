		<!-- #footer -->
		<footer id="footer">
			<p>Copyright 2011 - <a href="#">Plan du site</a> - <a href="#">Contact</a> - <a href="#">Mentions légales</a> - <a href="#">Crédits</a></p>
			<ul id="logos_footer">
				<li><a href="#"><img src="../images/visuels/logo_inter_rhone.png" alt="Inter Rhône" /></a></li>
				<li><a href="#"><img src="../images/visuels/logo_inter_vins.png" alt="Inter Vins" /></a></li>
				<li><a href="#"><img src="../images/visuels/logo_vins_provence.png" alt="Vins de Provence" /></a></li>
			</ul>
		</footer>
		<!-- fin #footer -->
	
	</div>
	<!-- fin #global -->

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 9 ]> </div> <![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
	
	<script type="text/javascript" src="<?php echo JS_PATH; ?>lib/jquery-1.4.2.min.js"></script>
	
	<?php 
		if(MODE == 'DEV') getJSSpec($js_spec);
		else getJSSpec($js_spec_min); 
	?>
</body>

</html>