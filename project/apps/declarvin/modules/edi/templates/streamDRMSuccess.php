<?php
foreach ($drms as $drm) {
  include_component('edi', 'viewDRM', array('drm' => $drm));
}