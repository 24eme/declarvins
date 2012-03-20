<tr>
	<th class="<?php echo ((isset($cssclass_libelle)) ? $cssclass_libelle : null) ?>"><?php echo $libelle ?></th>
	<?php foreach($colonnes as $col_key => $item): ?>
	<?php if(!is_null($item)): ?>
	<td class="<?php echo ((isset($cssclass_value)) ? $cssclass_value : null) ?>">
		<?php if(isset($partial)): ?>
			  <?php include_partial($partial, array('item' => $item, 'hash' => isset($hash) ? $hash : null)) ?>
		<?php elseif(isset($format)): ?>
		<?php echo call_user_func_array($format, 
										array_merge(array($item->get($hash)), 
												    $format_params->getRawValue())) ?>
		<?php else: ?>
		<?php echo $item->get($hash) ?>
		<?php endif; ?>
	</td>
	<?php else: ?>
	<td class="vide">&nbsp;</td>
	<?php endif; ?>
	<?php endforeach; ?>
</tr>