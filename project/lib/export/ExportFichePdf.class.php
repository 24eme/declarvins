<?php
class ExportFichePdf
{
	protected $contrat;
	protected $compte;
	
	public function __construct($contrat, $compte)
	{
		$this->contrat = $contrat;
		$this->compte = $compte;
	}
	
	protected static function getPartial($partial, $vars = null)
	{
		return sfContext::getInstance()->getController()->getAction('export', 'main')->getPartial('export/' . $partial, $vars);
	}
	
	public function render($response, $debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass('FICHE_'.$this->contrat->no_contrat.'.pdf');
		$document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$document->addHtml($this->getPartial('fiche_pdf', array('contrat' => $this->contrat, 'compte' => $this->compte)));
        $output = $document->output();
		$document->addHeaders($response);
		return $output;
	}
	
	public function generate($debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass('FICHE_'.$this->contrat->no_contrat.'.pdf');
		$document->removeCache();
		$document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
		$document->addHtml($this->getPartial('fiche_pdf', array('contrat' => $this->contrat, 'compte' => $this->compte)));
		$output = $document->output();
	}
	
}