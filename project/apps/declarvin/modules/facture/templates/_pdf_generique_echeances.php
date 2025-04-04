<?php
use_helper('Float');
use_helper('Date');
$chequesOrdre = $factureConfiguration->getOrdreCheques();
$echeances = $facture->getEcheancesPapillon();
?>
\begin{center}

\begin{minipage}[b]{1\textwidth}

\renewcommand{\arraystretch}{1.3}
\begin{tabular}{|p{0mm} p{87mm} | p{36mm} p{36mm} p{36mm}|}
            \hline
	\multicolumn{2}{|>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{Modalités de règlement}}} &
	\multicolumn{3}{>{\columncolor[rgb]{0.8,0.8,0.8}}c|}{\centering \small{\textbf{Références de facturation}}} \\
            \hline

        \CutlnPapillonEntete
        <?php if($facture->getNbPaiementsAutomatique() && $facture->getSociete()->getMandatSepa()): ?>
          &
          \centering \fontsize{7}{8}\selectfont \textbf{Prélevé} sur le compte \textbf{<?php echo $facture->getSociete()->getMandatSepa()->getBanqueNom() ?>} \\ ~ &

           \centering \small{Echéance} &
           \centering \small{Client~/~Facture} &
           \multicolumn{1}{c|}{\small{Montant TTC}} \\

                       \centering \small{~} &
                       \centering \fontsize{7}{8}\selectfont \textbf{RIB~}:~<?php echo $facture->getSociete()->getMandatSepa()->getRibFormate() ?>~ &

                       <?php if ($multiEcheances = $facture->getEcheancesArray(true)->getRawValue()): $first = true; foreach($multiEcheances as $echeance): ?>
                              <?php if (!$first): ?>
                              \centering \small{~} &
                              \centering \small{~} &
                              <?php endif; ?>
                              \centering \small{\textbf{<?php echo format_date($echeance->echeance_date,'dd/MM/yyyy'); ?>}} &
                              \centering \fontsize{8}{9}\selectfont \FactureRefCodeComptableClient~/~\FactureNum &
                              \multicolumn{1}{r|}{\small{\textbf{<?php echo echoArialFloat($echeance->montant_ttc); ?>~\texteuro{}}}}  \\
                       <?php $first = false; endforeach; else: ?>
                              \centering \small{\textbf{<?php echo format_date($facture->date_echeance,'dd/MM/yyyy'); ?>}} &
                              \centering \fontsize{8}{9}\selectfont \FactureRefCodeComptableClient~/~\FactureNum &
                              \multicolumn{1}{r|}{\small{\textbf{<?php echo echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}}  \\
                       <?php endif; ?>

        <?php else: ?>
        <?php $nb = count($echeances) ; foreach ($echeances as $key => $papillon) : ?>
        &
   \centering \textbf{Virement bancaire} : \InterproBANQUE ~ &

    \centering \small{Echéance} &
    \centering \small{Client~/~Facture} &
    \multicolumn{1}{c|}{\small{Montant TTC}} \\

                \centering \small{~} &
                \centering \textbf{IBAN~:}~\InterproIBAN~\textbf{BIC~:}~\InterproBIC \\
                \centering \fontsize{7}{8}\selectfont ou par chèque à l'ordre : <?php echo ($chequesOrdre)? $chequesOrdre : "Ordre chèque"; ?> &

<?php if ($multiEcheances = $facture->getEcheancesArray(true)->getRawValue()): ?>
<?php
    $first = true;
    foreach($multiEcheances as $echeance):
?>
<?php if (!$first): ?>
\centering \small{~} &
\centering \small{~} &
<?php endif; ?>
\centering \small{\textbf{<?php echo format_date($echeance->echeance_date,'dd/MM/yyyy'); ?>}} &
\centering \fontsize{8}{9}\selectfont \FactureRefCodeComptableClient~/~\FactureNum &
\multicolumn{1}{r|}{\small{\textbf{<?php echo echoArialFloat($echeance->montant_ttc); ?>~\texteuro{}}}}  \\
<?php $first = false; endforeach; else: ?>
                \centering \small{\textbf{<?php echo format_date($papillon->echeance_date,'dd/MM/yyyy'); ?>}} &
                \centering \fontsize{8}{9}\selectfont \FactureRefCodeComptableClient~/~\FactureNum &
                \multicolumn{1}{r|}{\small{\textbf{<?php echo echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}}  \\
<?php endif; ?>

        <?php endforeach; ?>
      <?php endif; ?>
      \CutlnPapillon
      \hline
\end{tabular}
\end{minipage}
\end{center}
