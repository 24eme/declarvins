<table>
	<thead>
		<tr>
			<th>DAI/DS</th>
			<th>Etat <a href="" class="msg_aide" data-msg="help_popup_daids_monespace_etat" title="Message aide"></a></th>
            <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                 <th>Mode de saisie</th>
            <?php endif; ?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0; ?>
		<?php if($new_daids): ?>
		<?php include_partial('daids/historiqueItem', array('alt' => $i%2 == 0, 
															   'daids' => $new_daids,
															   'etablissement' => $etablissement)); $i++; ?>
		<?php endif; ?>
		<?php foreach ($daids as $key => $d): ?>
			<?php include_partial('daids/historiqueItem', array('alt' => $i%2 == 0, 
															   'daids' => $d,
															   'etablissement' => $etablissement)) ?>
		<?php $i++; endforeach; ?>
	</tbody>
</table>
