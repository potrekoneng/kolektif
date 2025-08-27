<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Imagick;

class PreviewLastPhoto extends Controller
{
    function previewLastPhoto()
    {
        // Path ke folder lokal (perhatikan kamu harus pastikan akses ini diizinkan)
        // $directory = 'C:/Users/Test Drive/Pictures/Camera'; // Ganti dengan path yang sesuai
        $directory = getenv('USERPROFILE') . '\Pictures\Camera';

        // Ambil semua file gambar dari folder
        $files = File::allFiles($directory);

        // Urutkan berdasarkan waktu modifikasi, ambil file terbaru
        $latestFile = collect($files)->sortByDesc(function ($file) {
            return Carbon::createFromTimestamp(File::lastModified($file));
        })->first();

        // Periksa apakah ada file terbaru
        if ($latestFile) {
            // Kembalikan URL relatif untuk gambar tersebut
            return response()->json([
                'image' => asset('storage/my_picture/camera/' . basename($latestFile)),
            ]);
        }

        return response()->json(['message' => 'No images found.'], 404);
    }

    function preview()
    {
        return view('previewImage');
    }

    function cekMetaImage()
    {
        // $path = getenv('USERPROFILE') . '\Pictures\Camera';
        $directory = getenv('USERPROFILE') . '\Pictures\Camera';
        $files = File::allFiles($directory);

        $path = collect($files)->sortByDesc(function ($file) {
            return File::lastModified($file);
        })->first();

        if (!file_exists($path)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->json([
            'url' => '/latest-image',
            'last_modified' => gmdate('c', filemtime($path)), // ISO 8601 format
        ]);
    }


    public function show()
    {
        // $directory = 'C:/Users/Test Drive/Pictures/Camera';
        $directory = getenv('USERPROFILE') . '\Pictures\Camera';
        $files = File::allFiles($directory);

        $latestFile = collect($files)->sortByDesc(function ($file) {
            return File::lastModified($file);
        })->first();

        if (!$latestFile) {
            return response()->json(['message' => 'No images found.'], 404);
        }

        $path = $latestFile->getRealPath();
        $mime = File::mimeType($path);

        try {
            $image = new \Imagick($path);

            $image = new \Imagick($path);

            // Koreksi orientasi berdasarkan EXIF
            $orientation = $image->getImageOrientation();
            switch ($orientation) {
                case \Imagick::ORIENTATION_UNDEFINED:
                case \Imagick::ORIENTATION_TOPLEFT:
                    // sudah benar, tidak perlu rotasi
                    break;
                case \Imagick::ORIENTATION_TOPRIGHT:
                    $image->flopImage(); // horizontal flip
                    break;
                case \Imagick::ORIENTATION_BOTTOMRIGHT:
                    $image->rotateImage(new \ImagickPixel('none'), 180);
                    break;
                case \Imagick::ORIENTATION_BOTTOMLEFT:
                    $image->flipImage(); // vertical flip
                    break;
                case \Imagick::ORIENTATION_LEFTTOP:
                    $image->flopImage();
                    $image->rotateImage(new \ImagickPixel('none'), 90);
                    break;
                case \Imagick::ORIENTATION_RIGHTTOP:
                    $image->rotateImage(new \ImagickPixel('none'), 90);
                    break;
                case \Imagick::ORIENTATION_RIGHTBOTTOM:
                    $image->flopImage();
                    $image->rotateImage(new \ImagickPixel('none'), -90);
                    break;
                case \Imagick::ORIENTATION_LEFTBOTTOM:
                    $image->rotateImage(new \ImagickPixel('none'), -90);
                    break;
            }

            // Setelah rotasi, set orientasi ke normal agar tidak rotasi ganda saat render
            $image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

            $width = $image->getImageWidth();
            $height = $image->getImageHeight();

            $boxWidth = 2200;
            $boxHeight = 2750;

            $startX = max(0, ($width - $boxWidth) / 2);
            $startY = max(0, ($height - $boxHeight) / 2 - 150);

            // Buat layer hitam penuh ukuran gambar
            $blackLayer = new \Imagick();
            $blackLayer->newImage($width, $height, new \ImagickPixel('black'));
            $blackLayer->setImageFormat($image->getImageFormat());

            // Crop bagian tengah dari gambar asli (area yang tetap terlihat)
            $focusArea = clone $image;
            $focusArea->cropImage($boxWidth, $boxHeight, $startX, $startY);

            // Tempel bagian fokus ke layer hitam di posisi tengah
            $blackLayer->compositeImage($focusArea, \Imagick::COMPOSITE_COPY, $startX, $startY);

            // Output hasilnya
            header("Content-Type: $mime");
            echo $blackLayer;

            // Bersihkan memori
            $image->destroy();
            $blackLayer->destroy();
            $focusArea->destroy();

            exit;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing image', 'error' => $e->getMessage()], 500);
        }
    }
}
