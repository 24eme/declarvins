<?php require_once('../config/inc.php'); ?>

<?php 
	$titre_rub = "Déclaration Récapitulative Mensuelle";
	$titre_page = "Déclaration Récapitulative Mensuelle";
	$rub_courante = "DRM";
	$css_spec = "";
	
	array_push($js_spec, "drm.js");
	array_push($js_spec_min, "drm.js");
?>

<?php require('../includes/header.php'); ?>
	
	<?php require('../includes/nav_principale.php'); ?>
<section id="contenu">
	<section id="principal">
	<div class="clearfix" id="application_dr">
	    <h1>Produits <a href="/declarvin_dev.php/produit/nouveau">+</a></h1>
	    <div class="tableau_ajouts_liquidations">
	    <table class="tableau_recap">
            <thead>
    			<tr>
	<th><strong>Cat.</strong></th>
	<th><strong>Dénom.</strong></th>
	<th><strong>Lieu</strong></th>
	<th><strong>Couleur</strong></th>
	<th><strong>Cépage</strong></th>
	<th><strong>Millésime</strong></th>
	<th><strong>Dép.</strong></th>
	<th><strong>Labels</strong></th>  
	<th><strong>Douane</strong></th> 
	<th><strong>CVO</strong></th>    
	<th><strong>Repli</strong></th>   
	<th><strong>Déclassement</strong></th>    			
</tr>            </thead>
            <tbody>
	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Beaumes de venise</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Test</a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Blanc</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>84</td>
	<td>AB, ABC, AR, BD</td>
	<td>4.5% (L423)</td>
	<td>4.5%</td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rose-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rose-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Beaumes de venise</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rose-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Test</a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rose-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Rose</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rose-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rose-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>84</td>
	<td>AB, ABC, AR, BD</td>
	<td>4.5% (L423)</td>
	<td>4.5%</td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Beaumes de venise</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Test</a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Rouge</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-VDB-lieux-TST-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>84</td>
	<td>AB, ABC, AR, BD</td>
	<td>4.5% (L423)</td>
	<td>4.5%</td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-BEA-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-BEA-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Beaumes de Venise cru</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-BEA-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-BEA-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Rouge</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-BEA-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-BEA-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>84</td>
	<td>AB, ABC, AR, BD</td>
	<td>5.5% (L387_AOP)</td>
	<td>5.5%</td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-CGR-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-CGR-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Château Grillet</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-CGR-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-CGR-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Blanc</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-CGR-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-CGR-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td></td>
	<td>AB, ABC, AR, BD</td>
	<td></td>
	<td></td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Châteauneuf du Pape</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Blanc</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>84</td>
	<td>AB, ABC, AR, BD</td>
	<td></td>
	<td></td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Châteauneuf du Pape</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Rouge</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-CDP-lieux-DEFAUT-couleurs-rouge-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>84</td>
	<td>AB, ABC, AR, BD</td>
	<td></td>
	<td></td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-CDB-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-CDB-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Clairette de Bellegarde</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-CDB-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-CDB-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Blanc</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-CDB-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-CDB-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>30</td>
	<td>AB, ABC, AR, BD</td>
	<td>5% (L387_AOP)</td>
	<td>5%</td>	
	<td>ES</td>
	<td>ES</td>		
</tr>	    		    		<tr>
	<td><a href="/declarvin_dev.php/produit/certification/modification/declaration-certifications-AOP-appellations-COD-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">AOP</a></td>
	<td><a href="/declarvin_dev.php/produit/appellation/modification/declaration-certifications-AOP-appellations-COD-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Condrieu</a></td>
	<td><a href="/declarvin_dev.php/produit/lieu/modification/declaration-certifications-AOP-appellations-COD-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/couleur/modification/declaration-certifications-AOP-appellations-COD-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm">Blanc</a></td>
	<td><a href="/declarvin_dev.php/produit/cepage/modification/declaration-certifications-AOP-appellations-COD-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td><a href="/declarvin_dev.php/produit/millesime/modification/declaration-certifications-AOP-appellations-COD-lieux-DEFAUT-couleurs-blanc-cepages-DEFAUT-millesimes-DEFAUT" class="btn_ajouter btn_popup1" data-popup="popup_produit" data-popup-config="configForm"></a></td>
	<td>07, 38, 42, 69</td>
	<td>AB, ABC, AR, BD</td>
	<td>9% (L387_AOP)</td>
	<td>9%</td>	
	<td>ES</td>
	<td>ES</td>		
</tr>		    		    	</tbody>
    	</table>
	    </div>
	</div>
	</section>
</section>	
	
 <?php require('../includes/footer.php'); ?>
