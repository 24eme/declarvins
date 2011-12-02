<form id="form_appellation_ajout" action="<?php echo url_for('drm_recap_appellation_ajout_ajax', $label) ?>" method="post">
    <?php echo $form ?>
    <input type="submit" value="Ajouter" />
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('#form_appellation_ajout').submit( function () {
            $.post($(this).attr('action'), $(this).serializeArray(), 
            function (data) {
                if(data.success) {
                    document.location.href = data.url;
                } else {
                    $('#form_appellation_ajout').parent().html(data.content);
                }
            }, "json");
            return false;
        });
    })
</script>