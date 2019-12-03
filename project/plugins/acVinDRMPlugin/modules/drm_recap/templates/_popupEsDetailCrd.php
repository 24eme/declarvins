<div id="popup_details_entree_crd<?php echo str_replace('/', '_', $detail->getHash()) ?>" class="popup_contenu">
	<?php include_partial('esDetailCrdForm', array('form' => $form, 'detail' => $detail)) ?>
</div>
<script id="template_form_es_detail_crd" class="template_form" type="text/x-jquery-tmpl">
    <?php echo include_partial('form_es_detail_crd', array('form' => $form->getFormTemplateEsDetailCrd('var---nbItemDetail---'))); ?>
</script>