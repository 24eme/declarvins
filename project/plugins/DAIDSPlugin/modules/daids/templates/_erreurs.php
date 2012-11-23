<div class="error_list">
    <h3>Points bloquants</h3>
    <ol>
        <?php foreach ($daidsValidation->getErrors() as $identifiant => $error): ?>
            <li>
            	<a href="<?php echo ($error->getLien() != DAIDSValidation::NO_LINK) ? url_for('daids_show_error', array('sf_subject' => $daids,
				            										'type' => $error->getType(),
				            										'identifiant_controle' => $identifiant)) : DAIDSValidation::NO_LINK; ?>">
					<?php echo $error->getMessage(); ?>
            	</a>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
<br />