<?php

	/**
	 * Appelle les css spécifique à une page
	 *************************************************/
	function getCSSSpec($css_spec)
	{
		/* CSS spécifique à la page */
		if($css_spec)
		{
			$css_spec = explode(';', $css_spec);
			foreach($css_spec as $css)
				echo '<link rel="stylesheet/less" type="text/css" href="../css/'.$css.'" media="screen" />';
		}
	}
	
	
	/**
	 * Construit le menu de navigation principale
	 *************************************************/
	function getNavPrincipale($rub_courante)
	{
		$tab_nav = array
		(
			'accueil'=>'Accueil',
			'mon_profil'=>'Mon Profil',
			'vrac'=>'Vrac',
			'DRM'=>'DRM',
			'DAI_DS'=>'DAI/DS',
			'DR'=>'DR',
			'divers'=>'Divers'
		);
		
		$nav = '';

		foreach ($tab_nav as $rub_id => $rub_titre)
		{
			$classe = '';
			if($rub_id == $rub_courante) $classe = ' class="actif"';
			$nav .= '<li'.$classe.'><a href="#">'.$rub_titre.'</a></li>';
		}
		
		echo $nav;
	}
	
	
	/**
	 * Construit les étapes de la déclaration
	 *************************************************/
	function getDeclarationEtapes($etape_courante)
	{
		$tab_etapes = array ('Informations', 'Ajouts / Liquidations', 'AOP', 'IGP', 'Vins sans IG', 'Validation');
		
		$nav = '';
		$i = 1;

		foreach ($tab_etapes as $titre_etape)
		{
			$classe = ' class="';
			// Première étape
			if($i == 1) $classe .= 'premier ';
			// Etapes passées
			if($etape_courante > $i)
			{
				$classe .= 'passe ';
				$intitule = '<a href="#">'.$i.'. '.$titre_etape.'</a>';
			}
			else
			{
				// Etape courante
				if($etape_courante == $i) $classe .= 'actif ';
				$intitule = '<span>'.$i.'. '.$titre_etape.'</span>';
			}
			
			$classe .= '"';
			
			$nav .= '<li'.$classe.'>'.$intitule.'</li>';
			
			$i++;
		}
		
		echo $nav;
	}
?>
