<?php

namespace App\Helpers;

//use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\File;
use PDF;

class CertificateGenerator
{
//    public static function generate($name, $course, $date)
//    {
//        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificate.generator', compact('name', 'course', 'date'));
//        $pdf->save(public_path('storage/images/certificate/' . $name . '.pdf'));
//    }
    public static function generate($name, $course, $date)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificate.generator', compact('name', 'course', 'date'));
        $pdf->setPaper('A4', 'landscape'); // Set the orientation to landscape
        $pdf->save(public_path('storage/images/certificate/' . $name . '.pdf'));
    }



}
