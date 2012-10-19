<table>
	<thead>
		<tr>
			<th>DRM</th>
			<th>Etat <a href="" class="msg_aide" data-msg="help_popup_monespace_etat" title="Message aide"></a></th>
            <?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
                 <th>Mode de saisie</th>
            <?php endif; ?>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0; ?>
		<?php if($new_drm): ?>
		<?php include_partial('drm/historiqueItem', array('alt' => $i%2 == 0, 
															   'drm' => $new_drm,
															   'etablissement' => $etablissement)); $i++; ?>
		<?php endif; ?>
		<?php foreach ($drms as $drm): ?>
			<?php include_partial('drm/historiqueItem', array('alt' => $i%2 == 0, 
															   'drm' => $drm,
															   'etablissement' => $etablissement)) ?>
		<?php $i++; if (isset($limit) && $limit == $i) break; 
		endforeach; ?>
	</tbody>
</table>
