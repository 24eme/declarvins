<table>
	<thead>
		<tr>
			<th style="padding: 5px; width: 75px;">DRM</th>
			<th style="padding: 5px;">Etat <a href="" class="msg_aide" data-msg="help_popup_monespace_etat" title="Message aide"></a></th>
			<?php if ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR)): ?>
			<th style="padding: 5px;">Commentaires</th>
			<?php endif; ?>
                 <th style="padding: 5px; width: 155px;">Mode de saisie</th>

			<th style="padding: 5px; width: 100px;">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0; ?>
		<?php if($new_drm && !$historique): ?>
		<?php include_partial('drm/historiqueItem', array('alt' => $i%2 == 0, 
															   'drm' => $new_drm,
															   'etablissement' => $etablissement)); $i++; ?>
		<?php endif; ?>
		<?php foreach ($drms as $key => $drm): ?>
			<?php include_partial('drm/historiqueItem', array('alt' => $i%2 == 0, 
															   'drm' => $drm,
															   'etablissement' => $etablissement)) ?>
		<?php $i++; if (isset($limit) && $limit == $i) break; 
		endforeach; ?>
	</tbody>
</table>
