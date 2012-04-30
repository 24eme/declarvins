<?php

class CsvValidatedFile extends sfValidatedFile {

    protected $csv = null;
    protected $md5 = null;

    public function __construct($originalName, $type, $tempName, $size, $path = null) {
         parent::__construct($originalName, $type, $tempName, $size, $path);
         $this->csv = null;
    }

    public function setMd5($m) {
        $this->md5 = $m;
    }

    public function getMd5() {
        return $this->md5;
    }

    public function setCsv($csv) {
        $this->csv = $csv;
    }

    public function getCsv() {
        return $this->csv->getCsv();
    }

    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {

      $fc = file_get_contents($this->getTempName());
        $md5 = md5($fc);
        $file = $this->path . '/' . $md5;
        $this->setMd5($md5);
        $handle = fopen($file, "w+");
        fwrite($handle, $fc);
        fseek($handle, 0);
        fclose($handle);

        $this->savedName = $file;
    }

}

