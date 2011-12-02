<div id="forms_errors" style="color: red;">
    <?php include_partial('drm_recap/itemFormErrors', array('form' => $form)) ?>
</div>
<div id="saving_notification" style="display: none; position: fixed; top: 0; left: 50%; background: #114C8D; color: #fff">
    Sauvegarde en cours...
</div>
<div id="error_notification" style="display: none; position: fixed; top: 0; left: 50%; background: #900000; color: #fff">
    Il existe des erreurs !
</div>

<?php foreach($drm_appellation->couleurs as $couleur) :?>
    <?php foreach($couleur->details as $detail): ?>
        <?php include_component('drm_recap', 'itemForm', array('detail' => $detail, 'form' => $form)); ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('.form_detail').submit( function () {
            $.post($(this).attr('action'), $(this).serializeArray(), 
            function (data) {
                $('#saving_notification').hide();
                if(!data.success) {
                    $('#error_notification').show();
                }
                $('#forms_errors').html(data.content);
            }, "json");
            $('#error_notification').hide();
            $('#saving_notification').show();
            return false;
        });
    })
</script>
