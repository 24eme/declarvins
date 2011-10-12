<?php

class validationComponents extends sfComponents {
    
    public function executeFormUploadCsv(sfWebRequest $request) {
        $this->form = new UploadCSVForm();
    }
    
}