<?php
if($sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute):
  include_component('global', 'navTop', array('active' => 'factures'));
else:
  include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => 'factures'));
endif;
?>
<section id="contenu">
  <?php if ($sf_context->getInstance()->getRouting()->getCurrentRouteName() == 'facture'): ?>
    <div class="clearfix" id="application_dr">
        <div id="mon_compte">
            <?php include_partial('admin/etablissement_login_form', array('form' => new FactureSelectionForm($sf_user->getCompte()->getGerantInterpro()), 'route' => '@etablissement_login_factures')) ?>
        </div>
    </div>
  <?php endif; ?>
