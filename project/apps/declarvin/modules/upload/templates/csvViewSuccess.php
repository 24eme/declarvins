<?php
if ($iddrm) {
  echo $iddrm;
  return ;
 }
foreach ($errors as $e) {
  $type = 'COHERENCE';
  $line = '';
  if (isset($e['line'])) {
    $type = "LIGNE";
    $line = $e['line'];
  }
  echo $type.';'.$line.';'.$e['message'].";\n";
}
