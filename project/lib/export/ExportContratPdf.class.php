<?php
class ExportContratPdf extends ExportContrat 
{
	public function render($response, $debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->contrat->get('_id').'.pdf');
		$document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$contrat = $this->getContrat();
		$compte = $contrat->getCompteObject();
		$document->addHtml($this->getPartial('contrat_pdf', array('contrat' => $contrat, 'compte' => $compte)));
                $output = $document->output();
		$document->addHeaders($response);
		return $output;
	}
	
	public function generate($debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->contrat->get('_id').'.pdf');
		$document->removeCache();
		$document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$contrat = $this->getContrat();
		$compte = $contrat->getCompteObject();
		$document->addHtml($this->getPartial('contrat_pdf', array('contrat' => $contrat, 'compte' => $compte)));
		$output = $document->output();
	}
	
}