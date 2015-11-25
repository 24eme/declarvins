<script id="template_url_etablissement" class="template_form" type="text/x-jquery-tmpl">
    <?php 
    	$zones = $interpro->get('_id');
    	if ($etablissement) { 
    		$etablissement = $etablissement->getRawValue();
    		$zones = implode('|', array_keys($etablissement->zones->toArray()));
    	}
    	echo url_for('etablissement_autocomplete_byfamilles', array('interpro_id' => $zones, 'familles' => 'var---famille---', 'only_actif' => 1)) 
    ?>
</script>