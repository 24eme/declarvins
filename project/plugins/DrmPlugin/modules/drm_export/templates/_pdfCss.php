<style type="text/css">
	@page {
		margin: 0.5cm;
	}
	body {
		font-family: sans-serif;
		margin: 100px 0 20px 0;
		font-size: 10pt;
	}

	h1, h2, h3, h4, h5, p, table, div {
		margin: 0;
		font-size: inherit;
		padding: 0;
	}

	#header,
	#footer {
		position: fixed;
		left: 0;
		right: 0;
	}
	#header {
		top: 0;
		color: #000;
		border-bottom: 1px solid #000;
	}

	#header h1 {
		font-size: 11pt;
		margin: 0;
	}

	#header p {
		margin: 0;
	}

	#header p.date_validation{
		font-style: italic;
		font-size: 9pt;
	}

	#header table {
		border-collapse: collapse;
		width: 100%;
	}
	
	#header table tr td.premier {
		width: 700px;
	}
	
	#header table tr td, #header table tr th {
		text-align: left;
	}

	#header table tr th, #header table tr th {
		text-align: left;
	}

	#footer {
		bottom: 0;
		border-top: 1px solid #000;
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
		background-color: #000;
		color: #fff;
		margin: 0;
		padding: 0;
		display: inline;
	}

	table.recap {
		border-collapse: collapse;
		border-spacing: 0;
		margin-bottom: 10px;
	}

	table.recap tr td, table.recap tr th {
		border: 1px solid #000;
		padding: 0;
		text-align: left;
	}

	table.recap tr td.total, table.recap tr th.total {
		font-weight: bold;
	}

	table.recap tr th.detail {
		font-weight: normal;
	}

	table.recap tr td.vide {
		border: none;
	}

	table.recap tr th {
		width: 255px;
		word-wrap: break-word;
		white-space: normal;
		padding-left: 3px;
	}

	table.recap tr td {
		width: 103px;
	}

	table.recap tr td.libelle {
		text-align: center;
	}

	table.recap tr td.detail, table.recap tr td.total {
		text-align: center;
	}

	table.recap tr td.detail.number span.zero {
		color: #fff;
	}

	table.recap tr td.total.number span.zero {
		color: #aaa;
	}

	table.legende {
		font-size: 9pt;
	}

	table.legende th {
		text-align: left;
	}

	.page-number {
		text-align: center;
		font-size: 8pt;
	}
	.page-number:before {
		content: "f " counter(page);
	}
	hr {
		page-break-after: always;
		border: 0;
	}
</style>