<?php

namespace App\Http\Controllers;

use Imagick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D; // Use facade
use Milon\Barcode\Facades\DNS2DFacade as DNS2D; // Use facade

class BarcodeController extends Controller
{
    public function generateBarcode()
    {
        $data = '1234567890'; // Data yang ingin di-encode
        $type = 'C128'; // Jenis barcode (misalnya, Code 39)
        $width = 2;
        $height = 50;
        // Generate barcode
        $barcode = DNS1D::getBarcodePNG($data, $type, $width, $height);
        // Simpan barcode sebagai file gambar
        $barpath = 'barcodes/new' . uniqid() . '.png'; // Nama file unik
        Storage::disk('public')->put($barpath, base64_decode(str_replace('data:image/png;base64,', '', $barcode)));


        $filename = 'qrcode_' . time() . '.png'; // Nama file QR code
        $path = 'qrcodes/' . $filename; // Path penyimpanan
        // Generate QR code dan simpan sebagai file PNG
        $image = DNS2D::getBarcodePNGPath($data, 'QRCODE');
        // Simpan file ke direktori storage
        Storage::disk('public')->put($path, file_get_contents($image));

        //  Simpan path file ke database (opsional)
        //  ...

        // return view('barcode.index', ['barcodePath' => $barpath]);
    }

    public function generateAndSaveQRCode()
    {
        // echo DNS2D::getBarcodeHTML('9780691147727', 'QRCODE');
        $data = 'https://contohwebsite.com';
        // $qrCode = DNS2D::getBarcodeHTML($data, 'QRCODE');

        $image2838 = new Imagick('gfdgf');
        $image2838->setImageFormat('jpeg');
        $newImageName2838 =  'qrcode.png';
        $newImagePath2838 = public_path('uploads/' . $newImageName2838);
        $image2838->writeImage($newImagePath2838);
        $image2838->clear();
        $image2838->destroy();

        // // Menyimpan QR code ke dalam file
        // $path = public_path('uploads/qrcode.png');
        // file_put_contents($path, $qrCode);

        // return response()->download($path);
    }
}
