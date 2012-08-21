<div id="contenu">
	<form action="<?php echo url_for('vrac_nouveau', $this->etablissement) ?>" method="post">
		<?php echo $form ?>
		<input type="submit" value="Save" />
	</form>
</div>