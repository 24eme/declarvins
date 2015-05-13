<?php
class ExportVracPdfTransaction extends ExportVracPdf
{

	public function getContent() {
		return $this->getPartial('pdfTransaction', array('vrac' => $this->getVrac(), 'configurationVrac' => $this->getConfigurationVrac()));
	}
}