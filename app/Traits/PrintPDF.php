<?php
namespace App\Traits;

use Dompdf\Dompdf;

trait PrintPDF {
    /**
     * Print a html document as pdf with the help of the DomPDF library
     *
     * @param string $html
     * @param array $paper
     * @param string $name
     * @return Dompdf
     */
    public function printPDF($html, array $paper = ['A4', 'portrait'], string $name = 'document')
    {
        $print = new Dompdf();
        $print->loadHtml($html);
        $print->setPaper(...$paper);
        $print->render();

        return $print->stream($name);
    }
}
