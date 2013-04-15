<?php use_helper('Vrac'); ?>
<div class="tableau_ajouts_liquidations">
	<table id="tableau_recap" class="visualisation_contrat">    
	    <thead>
	        <tr>
	        	<th style="width: auto;">Statut<br /><a href="" class="msg_aide" data-msg="help_popup_vrac_statut" title="Message aide"></a></th>
	        	<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                <th>Mode de saisie</th>
                <?php endif; ?>
	            <th class="type">Type<br /><a href="" class="msg_aide" data-msg="help_popup_vrac_type" title="Message aide"></a></th>
	            <th>N° de Visa<br /><a href="" class="msg_aide" data-msg="help_popup_vrac_visa" title="Message aide"></a></th>
	            <th>Soussignés</th>   
	            <th>Produit</th>
	            <th>Vol. enlevé. / Vol. prop.</th>
	            <th>Prix (HT)</th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($vracs as $value): ?>
	        	<?php include_component('vrac', 'listItem', array('value' => $value->getRawValue(), 'etablissement' => $etablissement)) ?>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>
