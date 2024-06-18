<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
<?php if ($controles = sfConfig::get("app_metabase_controles_$modele")): ?>
    <div class="vigilance_list">
        <h3 style="font-size: 14px;margin-bottom:10px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
              <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
              <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
              <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"/>
            </svg>
            Controles
        </h3>
        <ul>
        <?php foreach ($controles as $controle): ?>
            <?php
                $url = $controle['url'];
                if (!empty($controle['filtres'])) {
                    foreach ($controle['filtres'] as $key => $value) {
                        if ($doc->exist($value)) {
                            $url = str_replace($key, $doc->get($value), $url);
                        }
                    }
                }
            ?>
            <li><a href="<?php echo $url ?>" style="color: #d68500;" target="_blank"><?php echo $controle['libelle'] ?></a></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php endif; ?>
