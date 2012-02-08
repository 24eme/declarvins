<?php 

	if($_GET)
	{
		$action = $_GET["action"];
		
		if($action == "ajouter_produit")
		{
			sleep(1);
			$appelation = $_POST['appelation'];
			$couleur = $_POST['couleur'];
			$label = $_POST['label'];
			$disponible = $_POST['disponible'];
			$stock_vide = $pas_mouvement = $coherence = '';
			if(isset($_POST['stock_vide'])) $stock_vide = 'checked="checked"';
			if(isset($_POST['pas_mouvement'])) $pas_mouvement = 'checked="checked"';
			if($disponible > 0) $coherence = 'disabled="disabled"';
			else $disponible = 0;
			?>
			<tr>			     
				<td>
					Côtes du Rhône
					<input type="hidden" name="aop_id" value="<?php echo $appelation; ?>" />
				</td>
				<td><?php echo $couleur; ?></td>
				<td><?php echo $label; ?></td>
				<td class="disponible">
					<input type="hidden" name="disponible" value="<?php echo $disponible; ?>">
					<?php echo $disponible; ?>HL
				</td>
				<td class="stock_vide"><input type="checkbox" name="tab_2-fe-1" <?php echo $stock_vide; ?> <?php echo $coherence; ?> /></td>
				<td class="pas_mouvement"><input type="checkbox" name="tab_2-fe-2" <?php echo $pas_mouvement; ?> /></td>
			</tr>
			<?php
			
		}
		
		/* Validation d'une colonne lors de la déclaration*/
		elseif($action == "valider_col")
		{
			sleep(1);
			$retour = array('id'=>'valeur');
			echo json_encode($retour);
		}
	}

?>