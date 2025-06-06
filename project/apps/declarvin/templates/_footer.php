		<!-- #footer -->
		<footer id="footer">
			<div class="copyright">
				<p>Copyright <?php echo date('Y') ?> - <a href="<?php echo url_for('@contact') ?>" target="_blank">Contact</a> - <a href="<?php echo url_for('@mentions') ?>" target="_blank">Mentions légales et Crédits</a></p>
			</div>
			<ul id="logos_footer">
				<li><a href="<?php echo url_for("contact"); ?>"><img src="<?php echo image_path('/images/visuels/logo_interpro-ir.png', true) ?>" alt="Inter Rhône" /></a></li>
				<li><a href="<?php echo url_for("contact"); ?>"><img src="<?php echo image_path('/images/visuels/logo_interpro-ivse.png?20211206', true) ?>" alt="Inter Vins" /></a></li>
				<li><a href="<?php echo url_for("contact"); ?>"><img src="<?php echo image_path('/images/visuels/logo_interpro-civp.png?20210817', true) ?>" alt="Vins de Provence" /></a></li>
			</ul>
		</footer>
		<!-- fin #footer -->
