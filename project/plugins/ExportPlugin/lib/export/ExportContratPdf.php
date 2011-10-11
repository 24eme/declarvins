<?php
class ExportContratPdf extends ExportContrat 
{
	public function render($response, $debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->contrat->get('_id').'.pdf');
		$document->addHtml($this->getPartial('contrat', array('contrat' => $this->getContrat())));
		$document->addHeaders($response);
		return $document->output();
	}
	
}