<?php

use Dompdf\Dompdf;

class PrintablePDF extends PrintableOutput {

    protected $pdf;
    protected $pdf_file;

    protected function init() {
        // create new PDF document
        $this->pdf = new DOMPDF();
        $this->pdf->set_paper(PrintableOutput::FORMAT_A4, PrintableOutput::ORIENTATION_LANDING);

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
 
    public function setPaper($format, $orientation) {
        $this->pdf->set_paper($format, $orientation);
    }

    public function isCached() {

        return file_exists($this->pdf_file);
    }

    public function removeCache() {
        if (file_exists($this->pdf_file)) {
            $unlink =  unlink($this->pdf_file);
        	$this->init();
        	return $unlink;
        }
        return true;
    }

    public function addHtml($html) {
    	$this->pdf->load_html($html);
    }

    public function generatePDF() {
		$this->pdf->render();
		if ($this->annexe) {
		    file_put_contents('/tmp/'.$this->filename,  $this->pdf->output());
            $annexes = '';
            foreach ($this->annexe as $k => $annexe) {
                $fname = '/tmp/'.$this->filename.'_annexe_'.$k;
		        file_put_contents($fname,  $annexe);
                $annexes .= $fname.' ';
            }
		    exec("pdftk /tmp/".$this->filename." ".$annexes." cat output ".$this->pdf_file);
		    unlink('/tmp/'.$this->filename);
            foreach ($this->annexe as $k => $annexe) {
    		    unlink('/tmp/'.$this->filename.'_annexe_'.$k);
            }
		} else {
		  file_put_contents($this->pdf_file,  $this->pdf->output());
		}
    }

    public function addHeaders($response) {
        $response->setHttpHeader('Content-Type', 'application/pdf');
        $response->setHttpHeader('Content-disposition', 'attachment; filename="' . basename($this->filename) . '"');
        $response->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $response->setHttpHeader('Content-Length', filesize($this->pdf_file));
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

    public function getFilename() {
        return $this->pdf_file;
    }
}
