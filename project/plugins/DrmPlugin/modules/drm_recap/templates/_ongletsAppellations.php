<ul>
    <li>
<?php foreach($config_appellation->getCertification()->appellations as $appellation): ?>
    <?php if($drm_appellation->getCertification()->appellations->exist($appellation->getKey())): ?>
        <a href="<?php echo url_for('drm_recap_appellation', $appellation) ?>" <?php if($appellation->getKey() == $config_appellation->getKey()): ?>class="actif"<?php endif; ?>>
            <?php echo $appellation->libelle ?>
        </a>
    <?php endif; ?>
<?php endforeach; ?>
    </li>
</ul>

<a href="#">Ajouter une appellation</a>
  
