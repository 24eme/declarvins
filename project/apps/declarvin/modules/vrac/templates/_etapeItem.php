<?php
$liClass = $class.' ';
if($actif == $num_etape+1) $liClass .= 'actif';
  else if($actif > $num_etape) $liClass .= 'passe';

 $href ='';
  if($num_etape == 0 && $vrac->etape == 0) $href = 'href="'.url_for('vrac_nouveau').'"';
  else if($vrac->etape >= $num_etape) $href = 'href="'.url_for($url_etape,$vrac).'"';
?>


<li class="<?php echo $liClass; ?>">
    <a <?php echo $href; ?>>
        <span <?php echo ($vrac->etape < $num_etape)? 'style="cursor: default;"' : '' ?> ><?php echo $num_etape+1;?>. <?php echo $label; ?></span> 
    </a>    
</li>


   
