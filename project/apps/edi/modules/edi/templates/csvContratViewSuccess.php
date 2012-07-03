<?php
foreach ($errors as $e) {
  echo $e['ligne'].";".$e['message']."\n";
}
echo "OK;$nb\n";