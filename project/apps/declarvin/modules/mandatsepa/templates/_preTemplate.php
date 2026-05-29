<?php
if($sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute):
  include_component('global', 'navTop', array('active' => 'factures'));
else:
  include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => 'factures'));
endif;
?>
<section id="contenu">
