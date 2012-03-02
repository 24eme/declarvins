<?php
class ExportDRMPdf extends ExportDRM 
{
	public function render($response, $debug = false)
	{
		$targetClass = ($debug)? 'PrintableHTML' : 'PrintablePDF';
		$document = new $targetClass($this->drm->get('_id').'.pdf');
		$document->addHtml($this->getPartial('drm', array('drm' => $this->getDRM())));
		$document->addHeaders($response);
		return $document->output();
	}
	
}