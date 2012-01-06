<ul id="onglets_principal">
    <?php foreach ($appellation_keys as $appellation_key): ?>
        <?php $appellation = $config_appellation->getLabel()->appellations->get($appellation_key); ?>
        <?php if ($appellation->getKey() == $config_appellation->getKey()): ?>
            <li class="actif">
                <strong>
                    <?php echo $appellation->libelle ?> 
                    <span class="completion completion_validee">(<?php echo $appellations_updated[$appellation_key] ?>/<?php echo $appellations[$appellation_key] ?>)</span>
                </strong>
            </li>
        <?php else: ?>
            <li>
                <a href="<?php echo url_for('drm_recap_appellation', $appellation) ?>">
                    <?php echo $appellation->libelle ?> 
                    <span class="completion">(<?php echo $appellations_updated[$appellation_key] ?>/<?php echo $appellations[$appellation_key] ?>)</span>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    <li class="ajouter"><a href="#">Ajouter Appelation</a></li>
</ul>

<script type="text/javascript">
    $(document).ready(function () {
        $('#onglets_principal .ajouter a').click(function () {
            $('#popup_appellation_ajout').toggle(); 
            return false;
        });
    })
</script>
