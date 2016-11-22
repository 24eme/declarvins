<?php use_helper('Edi'); ?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.seed.douane.finances.gouv.fr/">
<soapenv:Header/>
<soapenv:Body>
<ws:getInformation>
<numAccises>
<numAccise><?php echo $ea ?></numAccise>
</numAccises>
</ws:getInformation>
</soapenv:Body>
</soapenv:Envelope>