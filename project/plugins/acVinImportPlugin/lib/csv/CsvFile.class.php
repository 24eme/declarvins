<?php

class CsvFile
{

  protected $current_line = 0;
  protected $file = null;
  protected $separator = null;
  protected $csvdata = null;
  protected $ignore = null;

  public function getFileName() {
    return $this->file;
  }

  public function __construct($file = null, $ignore_first_if_comment = 1) {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '0'); // for infinite time of execution
    $this->ignore = $ignore_first_if_comment;
    $this->separator = ';';
    if (!$file)
      return ;
    if (!file_exists($file) && !preg_match('/^http/', $file))
      throw new Exception("Cannont access $file");

    if (!$this->isUtf8($file)) {
        $charset = $this->getCharset($file);
        exec('iconv -f '.$charset.' -t utf-8 '.$file.' > '.$file.'.tmp');
        if (filesize($file.".tmp")) {
            exec('mv '.$file.".tmp ".$file);
        }
    }
    $this->file = $file;
    $handle = fopen($this->file, 'r');
    if (!$handle) {
      throw new sfException('unable to open file: '.$this->file);
    }
    $buffer = fread($handle, 500);
    fclose($handle);
    $buffer = preg_replace('/$[^\n]*\n/', '', $buffer);
    if (!$buffer) {
      throw new Exception('invalid csv file; '.$this->file);
    }

    $virgule = explode(',', $buffer);
    $ptvirgule = explode(';', $buffer);
    $tabulation = explode('\t', $buffer);
    if (count($virgule) > count($ptvirgule) && count($virgule) > count($tabulation))
      $this->separator = ',';
    else if (count($tabulation) > count($ptvirgule))
      $this->separator = '\t';
  }

  public function getCsv()
  {
    if ($this->csvdata) {
      return $this->csvdata;
    }
    $handler = fopen($this->file, 'r');
    if (!$handler) {
      throw new Exception('Cannot open csv file anymore');
    }
    $this->csvdata = array();
    while (($data = fgetcsv($handler, 0, $this->separator)) !== FALSE) {
      if (!preg_match('/^(...)?#/', $data[0]) && !preg_match('/^$/', $data[0])) {
		$this->csvdata[] = $data;
      }
    }
    fclose($handler);
    return $this->csvdata;
  }

  private function getCharset($file) {
    $ret = exec('file -i '.$file);
    $charset = substr($ret, strpos($ret,'charset='));
    return str_replace('charset=','',$charset);
  }

  private function isUtf8($file)
  {
    exec('iconv -f UTF-8 -t UTF-8 ' . escapeshellarg($file) . ' > /dev/null 2>&1', $output, $code);
    return $code === 0;
  }
}
