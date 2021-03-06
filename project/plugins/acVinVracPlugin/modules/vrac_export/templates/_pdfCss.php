<style type="text/css">
	@page {
		margin: 160px 30px 0px 30px;
	}
	body {
		font-family: sans-serif;
		margin: 0;
		font-size: 9pt;
	}

	h1, h2, h3, h4, h5, p, table, div {
		margin: 0;
		font-size: inherit;
		padding: 0;
	}

        h1 {
	  font-size: 10pt;
        }

	p {
		margin-top: 5px;
	}
	.clauses p {
		margin-top: 0px;
		margin-bottom: 5px;
	}

	#header,
	#footer {
		position: fixed; left: 0px;
	
	}
	#header {
		font-size: 11pt;
		color: #000;
		padding: 0 0 30px;
		top: -160px;
	}

	#header h1 {
		font-size: 16pt;
		margin: 0;
	}

	#header p {
		margin: 0;
	}

	#header h1 span.date_validation{
		font-style: italic;
		font-size: 9pt;
		font-weight: normal;
	}

	#header h1 span.rectificative {
		background-color: #000;
		color: #fff;
	}

	#header table {
		border-collapse: collapse;
		width: 100%;
	}
	#header table tr td, #header table tr th {
		text-align: left;
	}

	#header table tr th, #header table tr th {
		text-align: left;
	}
	

	#footer {
	}
	#footer table {
		width: 100%;
		border-collapse: collapse;
		border: none;
	}
	#footer td {
		padding: 0;
		width: 50%;
		font-size: 9pt;
	}

	h2 {
		background-color: #ccc;
		color: #000;
		margin: 0;
		text-align: center;
		padding: 1px 0;
		display: block;
		margin-bottom: 4px;
	}

	table.recap {
		border-collapse: collapse;
		border-spacing: 0;
	}

	table.recap tr td, table.recap tr th {
		border: 1px solid #000;
		padding: 0;
		text-align: left;
		padding: 0 3px;
	}

	table.recap tr td.total, table.recap tr th.total {
		font-weight: bold;
	}

	table.recap tr th.detail {
		font-weight: normal;
	}

	table.recap tr th.vide, table.recap tr td.vide {
		border: none;
	}

	table.recap tr th {
		word-wrap: break-word;
		white-space: normal;
	}

	table.recap.volumes tr th {
		width: 252px;
	}

	table.recap.volumes tr td {
		width: 96px;
	}

	table.recap.droits_douane tr th {
		width: 193px;
	}

	table.recap.droits_douane tr td {
		width: 104px;
	}

	table.recap tr td.libelle {
		text-align: center;
		font-weight: bold;
	}

	table.recap tr td.number .unite {
		font-size: 8pt;
		font-weight: normal;
	}

	table.recap tr td.detail, table.recap tr td.total {
		text-align: center;
	}

	table.recap.volumes tr td.detail.number span.zero {
		color: #fff;
	}

	table.recap.volumes tr td.total.number span.zero {
		color: #999;
	}

	div.legende {
		font-size: 9pt;
	}

	table.codes_produit {
		border-collapse: collapse;
		border-spacing: 0;
	}

	table.codes_produit tr td {
		width: 361px;
	}
	

	table.recap tr td.counter {
		width: 12px;
		font-size:8pt;
		text-align: right;
	}
	

	table.recap tr td.counterNone {
		border: none;
	}

	.case_a_cocher_container {
		position: relative;
	}

	.case_a_cocher_container label {
/*		margin-left: 3px;
*/	}

	.case_a_cocher_croix {
		position: absolute;
		display: block;
		left: 0; 
		top: 0;
	}

	table.double_col {
		border-collapse: collapse;
		border-spacing: 0;
	}

	table.double_col tr td {
		width: 526px;
		vertical-align: top;
		margin: 0;
	}

	table.double_col tr td.col_left {
		border-right: 1px dashed #000;
		padding-right: 15px;
	}

	table.double_col tr td.col_right {
		border-left: 1px dashed #000;
		padding-left: 15px;
	}

	.page-number {
		text-align: center;
		font-size: 8pt;
	}
	.page-number:before {
		content: "f " counter(page);
	}
	.bloc_bottom {
		margin-bottom: 20px;
	}

	table.double_col tr td.col_right .bloc_bottom {
		margin-bottom: 10px;
	}

        td.blank {
	  background-color: #555;
	  color: #555;
        }

	hr {
		page-break-after: always;
		border: 0;
	}

	/* Tableau simple */
	.tableau_simple
	{
		border-collapse: collapse;
		margin: 15px 0;
	}

	.tableau_simple th,
	.tableau_simple td
	{
		border: 1px solid #333;
		padding: 5px 10px;
		text-align: center;
	}

	.tableau_simple th
	{
		background: #eee;
	}

	/* Lots */
	#lots { padding: 15px; }

	#lots table
	{
		border-collapse: collapse;
		width: 100%;
	}

	#lots table th,
	#lots table td
	{
		border: 1px solid #333;
		padding: 5px;
		text-align: center;
	}

	#lots table th
	{
		background: #eee;
	}

	#lots table th.vide
	{
		background: #fff;
	}

	#lots table th.num_lot
	{
		background: #fff;
		color: #000;
		width: 25%;	
	}

	#lots table th.cuves,
	#lots table th.millesimes,
	#lots table th.degre,
	#lots table th.allergenes
	{
		background: #ccc;
		text-align: left;
		width: 25%;
	}

	#lots table th.pourcentage
	{
		border-right-color: #eee;
	}

	#lots table th.degre + td,
	#lots table th.allergenes + td,
	#lots table td.pourcentage
	{
		border-right-color: #fff;
	}

	#lots table th.cuves,
	#lots table th.millesimes,
	#lots table .der_cat > *
	{
		border-bottom-width: 2px;
	}

	#lots table th.num_lot,
	#lots table .dernier > *
	{
		border-bottom-width: 3px;
	}
	
	.clauses 
	{
		font-size: 8px;
    	line-height: 1;
	}
	
	.clauses table
	{
		width: 100%;
	}
	
	.clauses table td
	{
		padding: 0 10px;
		width: 50%;
		vertical-align: top;
	}
	
	.clauses ol,
	.clauses ul
	{
		margin: 10px 0 10px 30px;
		padding: 0;
	}

</style>