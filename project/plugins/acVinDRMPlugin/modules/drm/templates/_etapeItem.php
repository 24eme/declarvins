<li class="<?php echo $cssclass ?> <?php echo ($numero_courant >= $numero) ? 'passe' : '' ?> <?php echo ($numero_courant == $numero) ? 'actif' : '' ?>">
      <a href="<?php echo $url ?>"<?php if (isset($title)): ?> title="<?php echo $title ?>"<?php endif; ?>>
            <span><?php echo $numero ?>. <?php echo $libelle ?></span>
      </a>
</li>