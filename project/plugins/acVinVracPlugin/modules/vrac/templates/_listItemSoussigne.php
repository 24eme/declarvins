<?php if($identifiant): ?>
      <span class="<?php if ($signature): ?>texte_vert<?php else: ?>texte_rouge<?php endif; ?>">
      <?php if($nom): ?>
          <?php echo $nom ?> 
      <?php endif; ?>
      <?php if($rs): ?>
          <?php echo ($nom)? ' / '.$rs : $rs; ?>   
      <?php endif; ?>
      </span>
	  <?php if ($signature): ?><br />Signé le <?php echo date('d/m/Y', strtotime($signature)) ?><?php endif; ?>
<?php endif; ?>