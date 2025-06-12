<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PDF Library
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Muhanz
 * @license			MIT License
 * @link			https://github.com/hanzzame/ci3-pdf-generator-library
 *
 */

require_once (APPPATH.'libraries/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;
class Pdf
{
    public function show($html, $filename)
    {
        // $html = '<b>Hello dd</b>';
        // $filename = 'Name';
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('defaultMediaType', 'all');
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'portrait');
        $widthInCm = 5.3; // Width in cm
        $heightInCm = 8.5; // Height in cm
        $widthInPoints = $widthInCm * 28.3465; // Convert width to points
        $heightInPoints = $heightInCm * 28.3465; // Convert height to points
    
        // Set custom paper size
        $dompdf->setPaper([0, 0, $widthInPoints, $heightInPoints], 'portrait');

        $dompdf->render();
        $dompdf->stream($filename . '.pdf', array("Attachment" => false)); 
        die();
    }

    public function download($html, $filename)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename . '.pdf'); 
        die();
    }

    public function generate($html, $filename = 'document.pdf', $download = true) {
        $dompdf = new Dompdf();
        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        if ($download) {
            $dompdf->stream($filename, array('Attachment' => 1));
        } else {
            $dompdf->stream($filename, array('Attachment' => 0));
        }
    }
}