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
	            <th>Acheteur</th>   
	            <th>Vendeur</th>   
	            <th>Courtier</th>
	            <th>Produit</th>
	            <th>Vol. enlevé. / Vol. prop.</th>
	            <th>Prix (HT)</th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php 
	        	$libelles = Vrac::getModeDeSaisieLibelles();
	        	$isOperateur = $sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR);
	        	foreach ($vracs as $value) {
	        		include_partial('listItem', array('elt' => $value->value, 'etablissement' => $etablissement, 'libelles' => $libelles, 'isOperateur' => $isOperateur));
	        	}
	        ?>
	    </tbody>
	</table>
</div>