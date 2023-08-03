<?php

namespace App\Helpers;

//use Barryvdh\DomPDF\PDF;
use App\Models\Certificate;
use Illuminate\Support\Facades\File;
use PDF;

class CertificateGenerator
{
    public static function generate($name, $course, $date, $course_id, $user_id)
    {
        $timeStamp = time(); // Generate the timestamp only once
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificate.generator', compact('name', 'course', 'date'));
        $pdf->setPaper('A4', 'landscape'); // Set the orientation to landscape
        $pdfPath = public_path('storage/images/certificate/' . $timeStamp . '.pdf'); // Use the same timestamp here
        $pdf->save($pdfPath);

        $certificate = Certificate::create([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'path' => 'storage/images/certificate/' . $timeStamp . '.pdf', // Use the same timestamp here
        ]);
    }



}
