<?php if($identifiant): ?>
    <?php echo $libelle ?>
    <a href="<?php echo url_for('vrac_etablissement', array('identifiant' => $identifiant)) ?>">
      <?php if($nom): ?>
          <?php echo $nom ?>
      <?php elseif($rs): ?>
          <?php echo $rs ?>
      <?php else: ?>
          <?php echo $identifiant; ?>    
      <?php endif; ?>
    </a>
<?php endif; ?>