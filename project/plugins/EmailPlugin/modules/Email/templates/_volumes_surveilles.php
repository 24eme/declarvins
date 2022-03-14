<?php echo include_partial('Email/headerMail') ?>

Une DRM contenant des volumes sous surveillance, vient d'être validée : <a href="<?php echo ProjectConfiguration::getAppRouting()->generate('drm_visualisation', array('sf_subject' => $drm), true); ?>"><?php $drm->_id ?></a><br />
Il s'agit de la DRM <strong><?php echo $drm->getMois() ?>/<?php echo $drm->getAnnee() ?></strong> de l'opérateur <strong><?php echo ($drm->declarant->nom)? $drm->declarant->nom : $drm->declarant->raison_sociale; ?></strong> (<?php echo $drm->identifiant ?>), comportant les volumes suivants :<br /><br />
<table>
    <thead>
        <tr>
            <th>
                Libelle
            </th>
            <th>
                Volume (hl)
            </th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($volumes as $libelle => $volume): ?>
    <tr>
        <td>
            <?php echo $libelle ?>
        </td>
        <td align="right">
            <?php echo $volume ?>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>

<?php echo include_partial('Email/footerMail') ?>
