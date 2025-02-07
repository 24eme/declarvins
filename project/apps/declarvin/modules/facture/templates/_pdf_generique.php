<?php
use_helper('Float');
use_helper('Display');

$interpro = ($facture->exist('interpro'))? $facture->interpro : null;
$factureConfiguration = FactureConfiguration::getInstance($interpro);
$prix_u_libelle = $factureConfiguration->getNomTaux();
$titre_type_facture = "Cotisation interprofessionnelle";
$qt_libelle = "Volume \\tiny{en hl}";
if($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS)){
    $qt_libelle = "Quantité";
    $prix_u_libelle = "Prix U.";
    $titre_type_facture = "";
}
$avoir = ($facture->total_ht <= 0);
include_partial('facture/pdf_generique_prelatex', array('pdf_titre' => $titre_type_facture, 'ressortissant' => $facture->declarant, 'factureConfiguration' => $factureConfiguration));
include_partial('facture/pdf_facture_def', array('facture' => $facture, 'factureConfiguration' => $factureConfiguration));
include_partial('facture/pdf_generique_entete', array('facture' => $facture, 'avoir' => $avoir, 'factureConfiguration' => $factureConfiguration));
?>
\centering

                        \renewcommand{\arraystretch}{1.4}
			\begin{tabular}{|p{120mm} |p{20mm}|p{12mm}|p{24mm}p{0mm}|}
            \hline
  			\rowcolor{lightgray}
                        \centering \small{\textbf{Libellé}} &
   			\centering \small{\textbf{<?php echo $qt_libelle; ?>}} &
                        \centering \small{\textbf{<?php echo $prix_u_libelle; ?>}} &
   			\centering \small{\textbf{Montant HT \tiny{en \texteuro{}}}} &
   			 \\
  			\hline
                      ~ & ~ & ~ & ~ &\\

<?php
$nb_pages = 0;
//Pour chaque ligne de facture :
foreach ($facture->lignes as $type => $typeLignes) {
  $line_nb++;
?>
    \small{\textbf{<?php echo escape_string_for_latex($typeLignes->getLibellePrincipal()); ?>}<?php if($typeLignes->getLibelleSecondaire()): ?> <?php echo escape_string_for_latex($typeLignes->getLibelleSecondaire()); ?><?php endif; ?>} &
    <?php if(!$factureConfiguration->isPdfLigneDetails()): ?>
    \multicolumn{1}{r|}{\small{<?php echoArialFloat($typeLignes->getQuantite()); ?>}} &
    \multicolumn{1}{r|}{\small{<?php echoArialFloat($typeLignes->getPrixUnitaire()); ?>}} &
    \multicolumn{1}{r}{\small{<?php echoArialFloat($typeLignes->montant_ht); ?>}} &
    <?php else: ?>
    \multicolumn{1}{r|}{~} &
    \multicolumn{1}{r|}{~} &
    \multicolumn{1}{r}{~} &
    <?php endif; ?>
    \\
    <?php
    foreach ($typeLignes->details as $prodHash => $produit) {
        if($factureConfiguration->isPdfLigneDetails()) {
            $line_nb++;
            include_partial('facture/pdf_generique_tableRow', array('produit' => $produit->getRawValue(), 'facture' => $facture, 'factureConfiguration' => $factureConfiguration));
        }
        //cas d'un besoin de changement de page
        if ($line_nb >= ($lines_per_page)) {
            //on ajoute des blancs
            ?>
            ~ & ~ & ~ & ~ &\\
            ~ & ~ & ~ & \multicolumn{1}{r}{\textbf{.../...}} &\\
            ~ & ~ & ~ & ~ &\\
            \hline
            \end{tabular}
            <?php
            //on fait un saut de page
            pdf_newpage();
            //on remet les entete du tableau
            ?>
            \centering
                        \renewcommand{\arraystretch}{1.4}
            			\begin{tabular}{|p{120mm} |p{20mm}|p{12mm}|p{24mm}p{0mm}|}
                        \hline
              			\rowcolor{lightgray}
                                    \centering \small{\textbf{Libellé}} &
               			\centering \small{\textbf{<?php echo $qt_libelle; ?>}} &
                                    \centering \small{\textbf{<?php echo $prix_u_libelle; ?>}} &
               			\centering \small{\textbf{Montant HT \tiny{en \texteuro{}}}} &
               			 \\
              			\hline
                                  ~ & ~ & ~ & ~ &\\
            <?php
            $nb_pages++;
            $line_nb = FactureLatex::NB_LIGNES_ENTETE;
        } // fin de nouvelle page
    }
}

$nb_blank = FactureLatex::MAX_LIGNES_PERPAGE - $line_nb - $total_lines_footer;
if ($nb_pages > 0) {
    $nb_blank += FactureLatex::NB_LIGNES_ENTETE - 1;
}
if ($nbEcheances = count($facture->echeances)) {
  $nb_blank -= ($nbEcheances-2);
}
for($i=0; $i<$nb_blank;$i++):
    ?>
~ & ~ & ~ & ~ &\\
<?php endfor; ?>
    \hline
    \end{tabular}
<?php echo include_partial('facture/pdf_generique_reglement', array('facture' => $facture, 'factureConfiguration' => $factureConfiguration));
if ($nb_echeances && !$avoir)
    include_partial('facture/pdf_generique_echeances', array('facture' => $facture, 'factureConfiguration' => $factureConfiguration));
?>
\end{document}
