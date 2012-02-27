<?php
if ($iddrm) {
  echo $iddrm;
  return ;
 }
foreach ($errors as $e) {
  echo $e['line'].';'.$e['message'].";\n";
}
