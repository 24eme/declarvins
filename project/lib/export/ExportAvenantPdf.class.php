<?php
class ExportAvenantPdf extends ExportContrat 
{
	public function render($response, $debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->contrat->get('_id').'_avenant.pdf');
		$document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$contrat = $this->getContrat();
		$compte = $contrat->getCompteObject();
		$convention = $compte->getConventionCiel();
		$document->addHtml($this->getPartial('contrat_pdf_avenant', array('contrat' => $contrat, 'compte' => $compte, 'convention' => $convention)));
        $output = $document->output();
		$document->addHeaders($response);
		return $output;
	}
	
	public function generate($debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->contrat->get('_id').'_avenant.pdf');
		$document->removeCache();
		$document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$contrat = $this->getContrat();
		$compte = $contrat->getCompteObject();
		$convention = $compte->getConventionCiel();
		$document->addHtml($this->getPartial('contrat_pdf_avenant', array('contrat' => $contrat, 'compte' => $compte, 'convention' => $convention)));
		$output = $document->output();
	}
	
}