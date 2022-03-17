<?php
if($sf_request->getAttribute('sf_route')->getRawValue() instanceof InterfaceEtablissementRoute):
  include_component('global', 'navTop', array('active' => 'sv12'));
else:
  include_component('global', 'navBack', array('active' => 'operateurs', 'subactive' => 'sv12'));
endif;
?>
<section id="contenu">
  <?php if ($sf_context->getInstance()->getRouting()->getCurrentRouteName() == 'sv12'): ?>
    <div class="clearfix" id="application_dr">
        <div id="mon_compte">
            <?php include_partial('admin/etablissement_login_form', array('form' => new EtablissementSelectionForm($sf_user->getCompte()->getGerantInterpro()->get('_id'), array(), array('sous_familles' => array(EtablissementFamilles::FAMILLE_NEGOCIANT => EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR))), 'route' => '@etablissement_login_sv12')) ?>
        </div>
    </div>
  <?php endif; ?>
