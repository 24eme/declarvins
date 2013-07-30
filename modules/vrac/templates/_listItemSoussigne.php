<?php if($identifiant): ?>
      <?php if($nom): ?>
          <?php echo $nom ?>
      <?php elseif($rs): ?>
          <?php echo $rs ?>
      <?php else: ?>
          <?php echo $identifiant; ?>    
      <?php endif; ?>
      <br />
      <?php if ($signature): ?>
      Signé le <?php echo date('d/m/Y H:i', strtotime($signature)) ?>
      <?php else: ?>
      Non signé
      <?php endif; ?>
<?php endif; ?>