<?php
class ExportRemisesPdf
{
	protected $remises;
	protected $compte;

	public function __construct($remises)
	{
		$this->remises = $remises;
	}

	protected static function getPartial($partial, $vars = null)
	{
		return sfContext::getInstance()->getController()->getAction('export', 'main')->getPartial('export/' . $partial, $vars);
	}

    private function getDocument($debug = false)
    {
        $targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
        $document = new $targetClass(date('Ymd').'_remises.pdf');
		$document->removeCache();
        $document->setPaper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_PORTRAIT);
        $document->addHtml($this->getPartial('remises_pdf', array('remises' => $this->remises)));
        return $document;
    }

	public function render($response, $debug = false)
	{
		$document = $this->getDocument($debug);
        $output = $document->output();
		$document->addHeaders($response);
		return $output;
	}

	public function generate($debug = false)
	{
		$document = $this->getDocument($debug);
		$output = $document->output();
        return $document->getFilename();
	}

}
