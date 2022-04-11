<?php use_helper('Date'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js">
<head>
	<title>Bordereaux des remises de paiement</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Actualys" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta name="Content-Language" content="fr-FR" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="copyright" content="Vins de Provence - 2011" />

	<style type="text/css" media="all">
		/**
		 * Reset
		 ******************************************/
		html, body, div, span, object, iframe,
		h1, h2, h3, h4, h5, h6, p, a, blockquote, pre,
		abbr, address, cite, code,
		del, dfn, em, img, ins, kbd, q, samp,
		small, strong, sub, sup, var,
		b, i,
		dl, dt, dd, ol, ul, li,
		fieldset, form, label, legend,
		table, caption, tbody, tfoot, thead, tr, th, td,
		article, aside, figure,  header,
		hgroup, menu, nav, section, menu,
		time, mark, audio, video
		{
			border: 0;
			font-size: 100%;
			margin: 0;
			outline: 0;
			padding: 0;
			vertical-align: baseline;
		}

		html, body { margin: 0 15pt; height: 100%; }
		a img, fieldset { border: 0; }
		a *, label, button, input[type=image], input[type=button], input[type=submit] { cursor: pointer; }
		ol { list-style-position: inside; }
		ul { list-style: none; }
		strong { font-weight: bold; }
		em { font-style: italic; }
		ins { text-decoration: none; }
		del { text-decoration: line-through; }
		sub { vertical-align: sub; font-size: smaller; }
		sup { vertical-align: super; font-size: smaller; }
		table { border-collapse: collapse; border-spacing: 0; }
		blockquote, q { quotes: none; }
		blockquote:before, blockquote:after,
		q:before, q:after { content: ''; content: none; }
		hr { border: 0; border-top: 1px solid #ccc; display: block; height: 1px; margin: 1em 0; padding: 0; }
		input, select { vertical-align: middle; }

		article, aside, figure,  header,
		hgroup, nav, section { display: block; }

		/**
		 * Eléments génériques
		 ******************************************/
		html
		{
			overflow-y: scroll;
			-webkit-font-smoothing: antialiased;
		}

		body
		{
			background: #fff;
			color: #000;
			font-family: Arial, Verdana, Helvetica, sans-serif;
			font-size: 14px;
			line-height: 1.2;
		}

		a { color: #000; text-decoration: none; }
		a:hover { text-decoration: underline; }

		h1, h2, h3, h4, h5, h6 { font-weight: bold; font-size: 18px; }

		textarea, input, select { font: 12px Arial, Verdana, Helvetica, sans-serif; }

		input[type=search] { -webkit-appearance: none; }
		input[type="radio"] { height: 14px; vertical-align: text-bottom; width: 14px; }
		input[type="checkbox"] { vertical-align: bottom; *vertical-align: baseline; }
		.ie6 input { vertical-align: text-bottom; }

		button { background: 0; border: 0; font-family: Arial, Verdana, Helvetica, sans-serif; margin: 0; outline: 0; overflow: visible; padding: 0; }
		button::-moz-focus-inner { border: 0; padding: 0; }
		button span { position: relative; margin: -1px 0 -2px; }

		pre, code, kbd, samp { font-family: monospace, sans-serif; }

		pre
		{
			padding: 15px;
			white-space: pre; /* CSS2 */
			white-space: pre-wrap; /* CSS 2.1 */
			white-space: pre-line; /* CSS 3 (and 2.1 as well, actually) */
			word-wrap: break-word; /* IE */
		}

		/**
		 * Structure générique
		 ******************************************/
		#global { padding: 15px 30px; }
		#global p,
		#global ul { margin: 12px 0; }
		#global ul { list-style: disc inside; padding: 0 0 0 20px; }
		#global .note { font-size: 11px; }
		#global .important { font-size: 13px; font-weight: bold; }
		#global .important p { margin: 10px 0; }
		#global .souligne { text-decoration: underline; }
		#global .page_break { page-break-after: always; }

		/**
		 * Entête
		 ******************************************/
		#entete_doc { margin: 0 0 20px; text-align: center; }
		#entete_doc h1 { font-size: 24px; margin: 0 0 20px; }
		#entete_doc h1 span { font-style: italic; }
		#entete_doc p { font-size: 16px; margin: 0 0 20px; }
		#entete_doc p.note { margin: 0; text-align: left; }

		/**
		 * Forumulaire
		 ******************************************/
		#formulaire { font-size: 13px; }
		#formulaire #declarant { margin: 0 0 30px; }
		#formulaire #declarant h2 {  }
		#formulaire #declarant p { margin: 0; }
		#formulaire .societe { margin: 0 0 10px; }

		/**
		 * Interprofessions
		 ******************************************/
		#interprofessions { font-size: 13px; margin: 0 0 30px; }


		/**
		 * Articles
		 ******************************************/
		#articles { margin: 0 0 20px;}
		#articles h2 { font-size: 16px; margin: 10px 0; }
		#articles h3 { font-family: "Times New Roman", Times, serif; font-size: 16px; font-style: italic; margin: 10px 0; }

                #footer {
                    position: fixed; left: 0px;
                }

                table {
                    width: 100%;
                }

                .remise {
                    margin-top: 45px;
                }

                .remise h1 {
                    text-align: center;
                }
                th, td {
                    padding: 5px 7px;
                }

        </style>
</head>

<body>

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 6 ]> <div class="ie6 ielt7 ielt8 ielt9"> <![endif]-->
<!--[if IE 7 ]> <div class="ie7 ielt8 ielt9"> <![endif]-->
<!--[if IE 8 ]> <div class="ie8 ielt9"> <![endif]-->
<!--[if IE 9 ]> <div class="ie9"> <![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->

<!-- #global -->
	<div id="global">


		<div id="formulaire">
			<?php
                $nb = count($remises);
                $i=0;
				foreach ($remises as $id => $paiements):
                    $i++;
                    if (!count($paiements)) {
                        continue;
                    }
                    $remiseInfo = $paiements[0];
			?>
            <div class="remise">
				<h1>Bordereau de remise n°&nbsp;<?php echo $id ?></h1>
                <br/><br/>
				<table>
                    <tr>
                        <td>
                            Date : <strong><?php echo format_date($remiseInfo[4], 'dd/MM/y', 'fr_FR'); ?></strong>
                        </td>
                        <td>
                            Type : <strong><?php echo $remiseInfo[6] ?></strong>
                        </td>
                        <td>
                            Compte : <strong><?php echo $remiseInfo[17] ?></strong>
                        </td>
                        <td>
                            Journal : <strong><?php echo $remiseInfo[15] ?></strong>
                        </td>
                    </tr>
                </table>
                <br/><br/>
            </div>
			<div class="paiements">
                <table>
                    <thead>
                    <tr>
                        <th align="center">Compte client</th>
                        <th align="center">Montant (€)</th>
                        <th align="center">N° pièce</th>
                        <th align="center">Date facture</th>
                        <th align="center">Tiré</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $nb = 0; $total = 0; foreach($paiements as $paiement): ?>
                        <tr>
                            <td align="center"><?php echo $paiement[2] ?></td>
                            <td align="right"><?php echo $paiement[5] ?></td>
                            <td align="center"><?php echo $paiement[3] ?></td>
                            <td align="center"><?php echo format_date($paiement[13], 'dd/MM/y', 'fr_FR'); ?></td>
                            <td><?php echo $paiement[1] ?></td>
                        </tr>
                    <?php $nb++; $total += round(str_replace(',', '.', $paiement[5]), 2); endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br/><br/>
            <table>
                <tr>
                    <td>
                        Nombre : <strong><?php echo $nb ?></strong>
                    </td>
                    <td>
                        Total : <strong><?php echo str_replace('.', ',', $total) ?>&nbsp;€</strong>
                    </td>
                </tr>
            </table>
			<?php if ($i < $nb): ?><div class="page_break">&nbsp;</div><?php endif; ?>
            <?php endforeach; ?>
		</div>

	</div>

<!-- fin #global -->

<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
<!--[if lte IE 9 ]> </div> <![endif]-->
<!-- ####### A REPRENDRE ABSOLUMENT ####### -->
</body>
</html>
