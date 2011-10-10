<?php

require_once(sfConfig::get('sf_lib_dir').'/vendor/dompdf/dompdf_config.inc.php');

class PrintablePDF extends PrintableOutput {

    protected $pdf;
    protected $pdf_file;

    protected function init() {
        // create new PDF document
        $this->pdf = new DOMPDF();
        $this->pdf->set_paper("A4", "portrait");

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
        $this->pdf->AddPage();
        $this->pdf->writeHTML($html);
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

    public function output() {
        if (!$this->isCached()) {
            $this->generatePDF();
        }
        return file_get_contents($this->pdf_file);
    }
}

