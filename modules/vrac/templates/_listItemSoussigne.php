<?php if($identifiant): ?>
      <span class="<?php if ($signature): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
      <?php if($nom): ?>
          <?php echo $nom ?>
      <?php elseif($rs): ?>
          <?php echo $rs ?>
      <?php else: ?>
          <?php echo $identifiant; ?>    
      <?php endif; ?>
      </span>
<?php endif; ?>