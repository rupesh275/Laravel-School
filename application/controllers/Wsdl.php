<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Wsdl extends Admin_Controller
{
    public function index()
    {
//         ini_set('display_errors', 1); 
// ini_set('display_startup_errors', 1); 
// error_reporting(E_ALL);

        $fromDate = '2021/05/22';
        $toDate = '2021/05/23';
        $serialNumber = 'ADZV211860817';

        $userName = 'sngcsweb';
        $userPassword = 'Sngcs@2022';
        $strDataList = '';
        $url = 'http://58.146.99.124:83/iclock/WebAPIService.asmx?op=GetTransactionsLog';
        // $url = 'http://111.93.157.53/iclock/WebAPIService.asmx?op=GetTransactionsLog';

        // xml post structure
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetTransactionsLog xmlns="http://tempuri.org/">
                <<FromDate>'.$fromDate.'</FromDate>
                <ToDate>'.$toDate.'</ToDate>
                <SerialNumber>'.$serialNumber.'</SerialNumber>
                <UserName>'.$userName.'</UserName>
                <UserPassword>'.$userPassword.'</UserPassword>
                <strDataList>'.$strDataList.'</strDataList>    
            </GetTransactionsLog>
          </soap:Body>
        </soap:Envelope>
        ';

        $headers = array(
            "Host: 58.146.99.124:83",
            "Content-Type: text/xml; charset=utf-8",
            "SOAPAction: http://tempuri.org/GetTransactionsLog",
            "Content-length: " . strlen($xml_post_string),
        );



        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $userName . ":" . $userPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
        // converting
        $response1 = str_replace("<soap:Body>", "", $response);
        $response2 = str_replace("</soap:Body>", "", $response1);

        // convertingc to XML
        $parser = simplexml_load_string($response2);
        echo $parser;
        echo 'End';
        // user $parser to get your data out of XML response and to display it. 
    }
}
