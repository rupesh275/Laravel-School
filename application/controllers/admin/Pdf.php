<?php

// defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Pdf extends Admin_Controller
{
    // public function show($html, $filename)
    public function show()
    {
        $html = '<b>Hello </b>';
        $filename = 'Name';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream($filename . '.pdf', array("Attachment" => false)); 
        die();
    }

    public function download($html, $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename . '.pdf'); 
        die();
    }
}
