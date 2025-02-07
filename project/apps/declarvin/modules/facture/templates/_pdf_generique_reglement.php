<?php
use_helper('Float');
?>


\noindent{
\begin{minipage}[b]{1\textwidth}

\noindent{
\begin{flushleft}
\begin{minipage}[b]{0.70\textwidth}
\begin{tiny}
<?php if ($facture->isAvoir()): ?>
   <?php echo $factureConfiguration->getReglementAvoir(); ?>
<?php else: ?>
   <?php echo $factureConfiguration->getReglement(); ?>
<?php endif; ?>
\end{tiny}
\end{minipage}
\end{flushleft}
}

\vspace{-5cm}

\begin{flushright}

\begin{minipage}[b]{0.25\textwidth}

\begin{flushright}

\renewcommand{\arraystretch}{2}
\begin{tabular}{| >{\columncolor{lightgray}} l | p{25mm} |}
\hline

\centering \textbf{\normalsize{Montant HT}} &
\multicolumn{1}{>{\raggedleft}p{25mm}|}{\normalsize{<?php echoArialFloat($facture->total_ht); ?>~\texteuro{}}} \\

\centering \textbf{\normalsize{TVA <?php echo number_format($facture->getTauxTva(), 1, '.', ' ');?>~\%}} &
\multicolumn{1}{>{\raggedleft}p{25mm}|}{\normalsize{<?php echoArialFloat($facture->taxe); ?>~\texteuro{}}} \\

\hline

\centering \textbf{\normalsize{Montant TTC}} &
\multicolumn{1}{>{\raggedleft}p{25mm}|}{\textbf{\normalsize{<?php echoArialFloat($facture->total_ttc); ?>~\texteuro{}}}}   \\

\hline
\end{tabular}

\end{flushright}

\end{minipage}
\end{flushright}
\end{minipage}
}

<?php if ($facture->getMessageCommunicationWithDefault()): ?>
\vspace{2.4cm}
<?php else: ?>
\vspace{2.6cm}
<?php endif; ?>
