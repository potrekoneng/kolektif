<?php

namespace App\Http\Controllers\Auth;

use Imagick;
use Carbon\Carbon;
use App\Models\User;
use App\Models\IdCard;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D; // Use facade
use Milon\Barcode\Facades\DNS2DFacade as DNS2D; // Use facade
use App\Models\ListQueue;
use App\Models\StudentCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PhotoController_old_save_public extends Controller
{
    public function prosesCorel($id)
    {
        $getUser = User::with('agency')->find($id);
        if (!$getUser || !$getUser->agency) {
            return response()->json(['error' => 'User atau agency tidak ditemukan'], 404);
        }

        $getList = ListQueue::whereDate('created_at', Carbon::today())
            ->where('agency_id', $getUser->agency->id)
            ->latest()
            ->first();

        $tipe = $getUser->agency->tipe;
        $tglIndo = Carbon::parse($getUser->tgl_lahir)->translatedFormat('j F Y');
        $tglToday = Carbon::now()->translatedFormat('j_F_Y');

        if (!$getList) {
            if ($getUser->agency->tipe === 'kartupelajar') {
                StudentCard::create([
                    'agency_id' => $getUser->agency->id,
                    'no1' => 1,
                    'name1' => $getUser->name,
                    'kelas1' => $getUser->kelas,
                    'alamat1' => $getUser->alamat,
                    'darah1' => $getUser->darah,
                    'agama1' => $getUser->agama,
                    'kelamin1' => $getUser->kelamin,
                    'nis1' => $getUser->nis,
                    'nisn1' => $getUser->nisn,
                    'ttl1' => $getUser->tmp_lahir . ', ' . $tglIndo,
                    'write_at1' => Carbon::now(),  // Menentukan waktu secara manual
                ]);
                $getStudentCard = StudentCard::whereDate('created_at', Carbon::today())
                    ->where('agency_id', $getUser->agency->id)
                    ->latest()
                    ->first();
                ListQueue::create([
                    'agency_id' => $getUser->agency->id,
                    'urutan' => 1,
                    'nomer' => 1,
                    'user_id' => $getUser->id,
                    'student_card_id' => $getStudentCard->id,
                ]);
                $this->prosesFoto($id, $tglToday, 1);
            } else {
                IdCard::create([
                    'agency_id' => $getUser->agency->id,
                    'no1' => 1,
                    'name1' => $getUser->name,
                    'kelas1' => $getUser->kelas,
                    'alamat1' => $getUser->alamat,
                    'darah1' => $getUser->darah,
                    'agama1' => $getUser->agama,
                    'kelamin1' => $getUser->kelamin,
                    'nis1' => $getUser->nis,
                    'nisn1' => $getUser->nisn,
                    'ttl1' => $getUser->tmp_lahir . ', ' . $tglIndo,
                    'write_at1' => Carbon::now(),  // Menentukan waktu secara manual
                ]);
                $getIdCard = IdCard::whereDate('created_at', Carbon::today())
                    ->where('agency_id', $getUser->agency->id)
                    ->latest()
                    ->first();
                // return response()->json(['message' => $getIdCard . ' Proses berhasil']);
                ListQueue::create([
                    'agency_id' => $getUser->agency->id,
                    'urutan' => 1,
                    'nomer' => 1,
                    'user_id' => $getUser->id,
                    'id_card_id' => $getIdCard->id,
                ]);
                $this->prosesFoto($id, $tglToday, 1);
            }
        } else {
            if ($tipe === 'kartupelajar') {
                $getStudentCard = StudentCard::whereDate('created_at', Carbon::today())
                    ->where('agency_id', $getUser->agency->id)
                    ->latest()
                    ->first();

                if (!$getStudentCard) {
                    return response()->json(['error' => 'StudentCard tidak ditemukan'], 404);
                }

                $nomer = $getList->nomer + 1;
                $urutan = $getList->urutan + 1;

                // Maksimal 10 siswa per kartu
                if (!is_null($getStudentCard->name10)) {
                    StudentCard::create([
                        'agency_id' => $getUser->agency->id,
                        'no1' => $nomer,
                        'name1' => $getUser->name,
                        'kelas1' => $getUser->kelas,
                        'alamat1' => $getUser->alamat,
                        'darah1' => $getUser->darah,
                        'agama1' => $getUser->agama,
                        'kelamin1' => $getUser->kelamin,
                        'nis1' => $getUser->nis,
                        'nisn1' => $getUser->nisn,
                        'ttl1' => $getUser->tmp_lahir . ', ' . $tglIndo,
                        'write_at1' => Carbon::now(),  // Menentukan waktu secara manual
                    ]);
                    $getStudentCard = StudentCard::whereDate('created_at', Carbon::today())
                        ->where('agency_id', $getUser->agency->id)
                        ->latest()
                        ->first();
                    ListQueue::create([
                        'agency_id' => $getUser->agency->id,
                        'nomer' => $nomer,
                        'urutan' => 1,
                        'user_id' => $getUser->id,
                        'student_card_id' => $getStudentCard->id,
                    ]);
                    $this->prosesFoto($id, $tglToday, $nomer);
                } else {
                    $student_card = StudentCard::find($getList->student_card_id); // gunakan ID dari ListQueue
                    $student_card->{'no' . $urutan} = $nomer;
                    $student_card->{'name' . $urutan} = $getUser->name;
                    $student_card->{'kelas' . $urutan} = $getUser->kelas;
                    $student_card->{'alamat' . $urutan} = $getUser->alamat;
                    $student_card->{'darah' . $urutan} = $getUser->darah;
                    $student_card->{'agama' . $urutan} = $getUser->agama;
                    $student_card->{'kelamin' . $urutan} = $getUser->kelamin;
                    $student_card->{'nis' . $urutan} = $getUser->nis;
                    $student_card->{'nisn' . $urutan} = $getUser->nisn;
                    $student_card->{'ttl' . $urutan} = $getUser->tmp_lahir . ', ' . $tglIndo;
                    $student_card->{'write_at' . $urutan} = Carbon::now();
                    $student_card->save();

                    // return response()->json(['message' => $getStudentCard . ' ' . $urutan]);

                    ListQueue::create([
                        'agency_id' => $getUser->agency->id,
                        'nomer' => $nomer,
                        'urutan' => $urutan,
                        'user_id' => $getUser->id,
                        'student_card_id' => $getStudentCard->id,
                    ]);
                    $this->prosesFoto($id, $tglToday, $nomer);
                }
            } else { // jika ID Card Kantor
                $getStudentCard = IdCard::whereDate('created_at', Carbon::today())
                    ->where('agency_id', $getUser->agency->id)
                    ->latest()
                    ->first();

                if (!$getStudentCard) {
                    return response()->json(['error' => 'StudentCard tidak ditemukan'], 404);
                }

                $nomer = $getList->nomer + 1;
                $urutan = $getList->urutan + 1;

                // Maksimal 5 Pegawai per kartu
                if (!is_null($getStudentCard->name5)) {
                    IdCard::create([
                        'agency_id' => $getUser->agency->id,
                        'no1' => $nomer,
                        'name1' => $getUser->name,
                        'kelas1' => $getUser->kelas,
                        'alamat1' => $getUser->alamat,
                        'darah1' => $getUser->darah,
                        'agama1' => $getUser->agama,
                        'kelamin1' => $getUser->kelamin,
                        'nis1' => $getUser->nis,
                        'nisn1' => $getUser->nisn,
                        'ttl1' => $getUser->tmp_lahir . ', ' . $tglIndo,
                        'write_at1' => Carbon::now(),  // Menentukan waktu secara manual
                    ]);
                    $getStudentCard = IdCard::whereDate('created_at', Carbon::today())
                        ->where('agency_id', $getUser->agency->id)
                        ->latest()
                        ->first();
                    ListQueue::create([
                        'agency_id' => $getUser->agency->id,
                        'nomer' => $nomer,
                        'urutan' => 1,
                        'user_id' => $getUser->id,
                        'id_card_id' => $getStudentCard->id,
                    ]);
                    $this->prosesFoto($id, $tglToday, $nomer);
                } else {
                    $student_card = IdCard::find($getList->id_card_id); // gunakan ID dari ListQueue
                    $student_card->{'no' . $urutan} = $nomer;
                    $student_card->{'name' . $urutan} = $getUser->name;
                    $student_card->{'kelas' . $urutan} = $getUser->kelas;
                    $student_card->{'alamat' . $urutan} = $getUser->alamat;
                    $student_card->{'darah' . $urutan} = $getUser->darah;
                    $student_card->{'agama' . $urutan} = $getUser->agama;
                    $student_card->{'kelamin' . $urutan} = $getUser->kelamin;
                    $student_card->{'nis' . $urutan} = $getUser->nis;
                    $student_card->{'nisn' . $urutan} = $getUser->nisn;
                    $student_card->{'ttl' . $urutan} = $getUser->tmp_lahir . ', ' . $tglIndo;
                    $student_card->{'write_at' . $urutan} = Carbon::now();
                    $student_card->save();

                    // return response()->json(['message' => $getStudentCard . ' ' . $urutan]);

                    ListQueue::create([
                        'agency_id' => $getUser->agency->id,
                        'nomer' => $nomer,
                        'urutan' => $urutan,
                        'user_id' => $getUser->id,
                        'id_card_id' => $getStudentCard->id,
                    ]);
                    $this->prosesFoto($id, $tglToday, $nomer);
                }
            }
        }

        return response()->json(['message' => $getUser->name . ' Proses berhasil']);
    }

    public function prosesFoto($id, $tglIndo, $nomer)
    {
        $getUser = User::with('agency')->find($id);

        if (!$getUser) {
            return response()->json(['error' => 'Peserta tidak ditemukan'], 404);
        }

        try {
            // Mendapatkan folder path berdasarkan user yang sedang login
            $userProfile = getenv('USERPROFILE');
            // $folderPath = 'C:\\Users\\Test Drive\\Pictures\\Camera';
            $folderPath = $userProfile . '\\Pictures\\Camera'; // Sesuaikan dengan struktur direktori
            $slugAgencyName = str_replace(' ', '_', $getUser->agency->name);
            $destinationFolder2025 = public_path('uploads/' . $slugAgencyName . '/' . $tglIndo . '/2025');
            $destinationFolder2227 = public_path('uploads/' . $slugAgencyName . '/' . $tglIndo . '/2227');
            $destinationFolder2838 = public_path('uploads/' . $slugAgencyName . '/' . $tglIndo . '/2838');
            $destinationFolder3858 = public_path('uploads/' . $slugAgencyName . '/' . $tglIndo . '/3858');
            // $destinationFolder2025 = storage_path($slugAgencyName . '/' . $tglIndo . '/2025');
            // $destinationFolder2227 = storage_path( $slugAgencyName . '/' . $tglIndo . '/2227');
            // $destinationFolder2838 = storage_path($slugAgencyName . '/' . $tglIndo . '/2838');
            // $destinationFolder3858 = storage_path( $slugAgencyName . '/' . $tglIndo . '/3858');

            // Cek apakah folder sudah ada
            if (!file_exists($destinationFolder2025)) {
                // Jika folder tidak ada, buat folder baru
                mkdir($destinationFolder2025, 0777, true);
            }
            // Cek apakah folder sudah ada
            if (!file_exists($destinationFolder2227)) {
                // Jika folder tidak ada, buat folder baru
                mkdir($destinationFolder2227, 0777, true);
            } // Cek apakah folder sudah ada
            if (!file_exists($destinationFolder2838)) {
                // Jika folder tidak ada, buat folder baru
                mkdir($destinationFolder2838, 0777, true);
            }
            if (!file_exists($destinationFolder3858)) {
                // Jika folder tidak ada, buat folder baru
                mkdir($destinationFolder3858, 0777, true);
            }

            $files = glob($folderPath . '/*.{jpg,JPG,jpeg,png,gif,bmp}', GLOB_BRACE);

            if (empty($files)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada file gambar dalam folder.',
                ]);
            }

            $latestFile = collect($files)->sortByDesc(fn($file) => (new \SplFileInfo($file))->getMTime())->first();

            if ($latestFile) {
                $image2025 = new Imagick($latestFile);
                $image2025->autoOrient();
                $image2025->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
                $image2025->setResolution(300, 300);
                $image2025->setImageResolution(300, 300);
                $image2025->stripImage();
                $image2025->setImageFormat('jpeg');
                // Crop the image (2000x2500 pixels) from the center
                $cropWidth20 = 2000;
                $cropHeight25 = 2500;
                // Get the current dimensions of the image
                $imageWidth20 = $image2025->getImageWidth();
                $imageHeight25 = $image2025->getImageHeight();
                // Calculate the center of the image
                $cropX20 = ($imageWidth20 - $cropWidth20) / 2;
                $cropY25 = ($imageHeight25 - $cropHeight25) / 2 - 350;
                // Crop from the center
                $image2025->cropImage($cropWidth20, $cropHeight25, $cropX20, $cropY25);
                // Resize the image to 2.2x2.7 cm at 300 DPI
                $resizeWidthCm20 = 2.0;
                $resizeHeightCm25 = 2.5;
                // Convert cm to pixels at 300 DPI
                $resizeWidthPx20 = (int)($resizeWidthCm20 * 300 / 2.54);
                $resizeHeightPx25 = (int)($resizeHeightCm25 * 300 / 2.54);
                $image2025->resizeImage($resizeWidthPx20, $resizeHeightPx25, Imagick::FILTER_LANCZOS, 1);
                // Save the new image
                // $newImageName = 'output_' . pathinfo($latestFile, PATHINFO_FILENAME) . '.jpg';
                $slugName = str_replace(' ', '_', $getUser->name);
                $newImageName2025 =  $nomer . '_' . $slugName . '_' . $getUser->nisn . '.jpg';
                $newImagePath2025 = $destinationFolder2025 . DIRECTORY_SEPARATOR . $newImageName2025;
                $image2025->writeImage($newImagePath2025);
                $image2025->clear();
                $image2025->destroy();

                $image2227 = new Imagick($latestFile);
                $image2227->autoOrient();
                $image2227->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
                $image2227->setResolution(300, 300);
                $image2227->setImageResolution(300, 300);
                $image2227->stripImage();
                $image2227->setImageFormat('jpeg');
                // Crop the image (2000x2500 pixels) from the center
                $cropWidth22 = 2200;
                $cropHeight27 = 2700;
                // Get the current dimensions of the image
                $imageWidth22 = $image2227->getImageWidth();
                $imageHeight27 = $image2227->getImageHeight();
                // Calculate the center of the image
                $cropX22 = ($imageWidth22 - $cropWidth22) / 2;
                $cropY27 = ($imageHeight27 - $cropHeight27) / 2 - 250;
                // Crop from the center
                $image2227->cropImage($cropWidth22, $cropHeight27, $cropX22, $cropY27);
                // Resize the image to 2.2x2.7 cm at 300 DPI
                $resizeWidthCm22 = 2.2;
                $resizeHeightCm27 = 2.7;
                // Convert cm to pixels at 300 DPI
                $resizeWidthPx22 = (int)($resizeWidthCm22 * 300 / 2.54);
                $resizeHeightPx27 = (int)($resizeHeightCm27 * 300 / 2.54);
                $image2227->resizeImage($resizeWidthPx22, $resizeHeightPx27, Imagick::FILTER_LANCZOS, 1);
                // Save the new image
                // $newImageName = 'output_' . pathinfo($latestFile, PATHINFO_FILENAME) . '.jpg';
                $newImageName2227 =  $nomer . '_' . $slugName . '_' . $getUser->nisn . '.jpg';
                $newImagePath2227 = $destinationFolder2227 . DIRECTORY_SEPARATOR . $newImageName2227;
                $image2227->writeImage($newImagePath2227);
                $image2227->clear();
                $image2227->destroy();

                $image2838 = new Imagick($latestFile);
                $image2838->autoOrient();
                $image2838->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
                $image2838->setResolution(300, 300);
                $image2838->setImageResolution(300, 300);
                $image2838->stripImage();
                $image2838->setImageFormat('jpeg');
                // Crop the image (2000x2500 pixels) from the center
                $cropWidth28 = 2066;
                $cropHeight38 = 2802;
                // Get the current dimensions of the image
                $imageWidth28 = $image2838->getImageWidth();
                $imageHeight38 = $image2838->getImageHeight();
                // Calculate the center of the image
                $cropX28 = ($imageWidth28 - $cropWidth28) / 2;
                $cropY38 = ($imageHeight38 - $cropHeight38) / 2 - 200;
                // Crop from the center
                $image2838->cropImage($cropWidth28, $cropHeight38, $cropX28, $cropY38);
                // Resize the image to 2.2x2.7 cm at 300 DPI
                $resizeWidthCm28 = 2.8;
                $resizeHeightCm38 = 3.8;
                // Convert cm to pixels at 300 DPI
                $resizeWidthPx28 = (int)($resizeWidthCm28 * 300 / 2.54);
                $resizeHeightPx38 = (int)($resizeHeightCm38 * 300 / 2.54);
                $image2838->resizeImage($resizeWidthPx28, $resizeHeightPx38, Imagick::FILTER_LANCZOS, 1);
                // Save the new image
                // $newImageName = 'output_' . pathinfo($latestFile, PATHINFO_FILENAME) . '.jpg';
                $newImageName2838 =  $nomer . '_' . $slugName . '_' . $getUser->nisn . '.jpg';
                $newImagePath2838 = $destinationFolder2838 . DIRECTORY_SEPARATOR . $newImageName2838;
                $image2838->writeImage($newImagePath2838);
                $image2838->clear();
                $image2838->destroy();

                $image3858 = new Imagick($latestFile);
                $image3858->autoOrient();
                $image3858->setImageUnits(Imagick::RESOLUTION_PIXELSPERINCH);
                $image3858->setResolution(300, 300);
                $image3858->setImageResolution(300, 300);
                $image3858->stripImage();
                $image3858->setImageFormat('jpeg');
                // Crop the image (2000x2500 pixels) from the center
                $cropWidth38 = 1724;
                $cropHeight58 = 2632;
                // Get the current dimensions of the image
                $imageWidth38 = $image3858->getImageWidth();
                $imageHeight58 = $image3858->getImageHeight();
                // Calculate the center of the image
                $cropX38 = ($imageWidth38 - $cropWidth38) / 2;
                $cropY58 = ($imageHeight58 - $cropHeight58) / 2 - 250;
                // Crop from the center
                $image3858->cropImage($cropWidth38, $cropHeight58, $cropX38, $cropY58);
                // Resize the image to 2.2x2.7 cm at 300 DPI
                $resizeWidthCm38 = 3.8;
                $resizeHeightCm58 = 5.8;
                // Convert cm to pixels at 300 DPI
                $resizeWidthPx38 = (int)($resizeWidthCm38 * 300 / 2.54);
                $resizeHeightPx58 = (int)($resizeHeightCm58 * 300 / 2.54);
                $image3858->resizeImage($resizeWidthPx38, $resizeHeightPx58, Imagick::FILTER_LANCZOS, 1);
                // Save the new image
                // $newImageName = 'output_' . pathinfo($latestFile, PATHINFO_FILENAME) . '.jpg';
                $slugName = str_replace(' ', '_', $getUser->name);
                $newImageName3858 =  $nomer . '_' . $slugName . '_' . $getUser->nisn . '.jpg';
                $newImagePath3858 = $destinationFolder3858 . DIRECTORY_SEPARATOR . $newImageName3858;
                $image3858->writeImage($newImagePath3858);
                $image3858->clear();
                $image3858->destroy();

                $getUser->update([
                    'photo' => $newImageName2025,
                ]);

                $this->genBarQr($id, $tglIndo, $nomer);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Gambar berhasil dicrop dari tengah dan diresize.',
                    'original_image' => basename($latestFile),
                    'new_image' => $newImageName2838,
                    'new_image_path' => $newImageName2838,
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menemukan file terbaru.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function genBarQr($id, $tglIndo, $nomer)
    {
        $getUser = User::with('agency')->find($id);
        $slugName = str_replace(' ', '_', $getUser->name);
        $slugAgencyName = str_replace(' ', '_', $getUser->agency->name);

        $data = $getUser->nis; // Data yang ingin di-encode
        $type = 'C128'; // Jenis barcode (misalnya, Code 39)
        $width = 2;
        $height = 50;

        $name =  $nomer . '_' . $slugName . '_' . $getUser->nisn . '.png';
        // Generate barcode
        $barcode = DNS1D::getBarcodePNG($data, $type, $width, $height);
        // Simpan barcode sebagai file gambar
        $barpath = $slugAgencyName . '/' . $tglIndo . '/barcodes/' . $name; // Nama file unik
        Storage::disk('public')->put($barpath, base64_decode(str_replace('data:image/png;base64,', '', $barcode)));


        // $filename = 'qrcode_' . time() . '.png'; // Nama file QR code
        // Generate QR code dan simpan sebagai file PNG
        $image = DNS2D::getBarcodePNGPath($data, 'QRCODE');
        // $path = 'qrcodes/' . $name; // Path penyimpanan
        $qrpath = $slugAgencyName . '/' . $tglIndo . '/qrcodes/' . $name; // Nama file unik
        // Simpan file ke direktori storage
        Storage::disk('public')->put($qrpath, file_get_contents($image));
        unlink($image); // Menghapus file sementara dari disk
    }

    function lastCropPicture()
    {
        $path = storage_path('SMA_TEST_FASE/2025');

        // $path = storage_path('app/public/images');

        $files = collect(File::files($path));

        if ($files->isEmpty()) {
            return response()->json(['url' => null]);
        }

        $latest = $files->sortByDesc(fn($file) => $file->getMTime())->first();

        // Buat URL yang sesuai dengan lokasi file-nya
        $relativePath = str_replace(public_path(), '', $latest->getPathname());
        $url = asset($relativePath);

        return response()->json(['url' => $url]);
    }
}
