<?php

require_once(sfConfig::get('sf_plugins_dir').'/sfDompdfPlugin/lib/vendor/dompdf/dompdf_config.inc.php');


class PrintablePDF extends PrintableOutput {

    protected $pdf;
    protected $pdf_file;

    protected function init() {
        // create new PDF document
        $this->pdf = new DOMPDF();
        $this->pdf->set_paper("a4", "portrait");

         /* Defaulf file_dir */
        if (!$this->file_dir) {
            umask(0002);
            $this->file_dir = sfConfig::get('sf_cache_dir').'/pdf/';
            if (!file_exists($this->file_dir)) {
                mkdir($this->file_dir);
            }
        }
        /******************/

        $this->pdf_file = $this->file_dir.$this->filename;

    }

    public function isCached() {
        return file_exists($this->pdf_file);
    }

    public function removeCache() {
        if (file_exists($this->pdf_file))
            return unlink($this->pdf_file);
        return true;
    }

    public function addHtml($html) {
    	$this->pdf->load_html($html);
    }

    public function generatePDF($no_cache = false) {
        if (!$no_cache && $this->isCached()) {
            return true;
        } else {
            $this->removeCache();
        }        
		$this->pdf->render();
		file_put_contents($this->pdf_file, $this->pdf->output());
    }

    public function addHeaders($response) {
        $response->setHttpHeader('Content-Type', 'application/pdf');
        $response->setHttpHeader('Content-disposition', 'attachment; filename="' . basename($this->filename) . '"');
        $response->setHttpHeader('Content-Transfer-Encoding', 'binary');
        //$response->setHttpHeader('Content-Length', filesize($this->pdf_file));
        $response->setHttpHeader('Pragma', '');
        $response->setHttpHeader('Cache-Control', 'public');
        $response->setHttpHeader('Expires', '0');
    }

    public function output() {
        if (!$this->isCached()) {
            $this->generatePDF();
        }
        return file_get_contents($this->pdf_file);
    }
}

