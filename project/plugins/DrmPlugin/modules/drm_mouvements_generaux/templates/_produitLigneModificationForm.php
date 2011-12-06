<tr>
    <td align="center"><?php echo ConfigurationClient::getCurrent()->declaration->labels->get($object->getParent()->getKey())->appellations->get($object->appellation)->libelle ?></td>
	<td align="center"><?php echo $object->couleur ?></td>
	<td align="center"><?php echo $object->denomination ?></td>
	<td align="center"><?php echo $object->label ?></td>
	<td align="center"><?php echo $object->disponible ?></td>
	<td align="center"><?php echo $form['stock_vide']->render() ?></td>
	<td align="center"><?php echo $form['pas_de_mouvement']->render() ?></td>
	<td align="center"></td>
</tr>