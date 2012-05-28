<?php
class ExportContratPdf extends ExportContrat 
{
	public function render($response, $debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->contrat->get('_id').'.pdf');
		$contrat = $this->getContrat();
		$compte = $contrat->getCompteObject();
		$document->addHtml($this->getPartial('contrat', array('contrat' => $contrat, 'compte' => $compte)));
		$output = $document->output();
		$document->addHeaders($response);
		return $output;
	}
	
}