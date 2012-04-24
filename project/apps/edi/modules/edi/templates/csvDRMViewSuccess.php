<?php
if ($iddrm) {
  echo "OK;;;$iddrm\n";
  return ;
 }
foreach ($errors->getRawValue() as $e) {
  $type = 'COHERENCE';
  $line = '';
  if (isset($e['line'])) {
    $type = "LIGNE";
    $line = $e['line'];
  }
  echo 'ERREUR;'.$type.';'.$line.';"'.$e['message']."\";\n";
}
