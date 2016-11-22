<?php include_component('global', 'navTop', array('active' => 'drm')); ?>


<section id="contenu">
<section id="principal">

<div style="text-align: center;"><center>
<img src="/images/declarvins2douane.gif" width="600" height="150"/>
<p>Transmission des données à pro.douane.gouv.fr en cours... <br/>Veuillez patienter</p>
</center></div>
<form id="form_transmission" method="post" action="<?php echo url_for('drm_validation', $drm); ?>">
	<?php foreach ($postVars as $id => $vars): ?>
		<?php foreach ($vars as $name => $value): ?>
		<input type="hidden" name="<?php echo $id.'['.$name.']'; ?>" value="<?php echo $value ?>" />
		<?php endforeach; ?>
	<?php endforeach; ?>
</form>
<script src="/js/lib/jquery-ui-1.8.21.min.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
           setTimeout("$('#form_transmission').submit();", 1000);
        });
</script>

    </section>
</section>