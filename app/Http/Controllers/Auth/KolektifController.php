<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use Illuminate\Support\Str;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Imagick;

class KolektifController extends Controller
{
    function agent()
    {
        $data = Agency::latest()->get();

        return Inertia::render('auth/Agencies', [
            'tableData' => $data
        ]);
    }

    function roles()
    {
        $data = Role::latest()->get();

        return Inertia::render('auth/Roles', [
            'tableData' => $data
        ]);
    }

    function isiAgent($id, Request $request)
    {
        $getAgent = Agency::where('id', $id)->first();
        if (!$getAgent) {
            return redirect('kolektif');
        }

        $search = $request->input('search');

        $users = User::query()
            ->where('agency_id', $id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return Inertia::render('auth/Users', [
            'users' => $users,
            'agency' => $getAgent,
            'filters' => $request->only('search'),
        ]);
    }

    function isiAgentSpesific($id, $tipe, Request $request)
    {
        $getAgent = Agency::where('id', $id)->first();
        if (!$getAgent) {
            return redirect('kolektif');
        }

        $search = $request->input('search');

        if ($tipe == 'pending') {
            $users = User::query()
                ->where('agency_id', $id)
                ->where('photo', null)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    });
                })
                ->paginate(10)
                ->withQueryString();
        } else {
            $users = User::query()
                ->where('agency_id', $id)
                ->where('nisn', 'like', '8888%')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    });
                })
                ->paginate(10)
                ->withQueryString();
        }

        if ($request->wantsJson()) {
            return response()->json($users);
        }

        return Inertia::render('auth/SpesificUsers', [
            'tipe' => $tipe,
            'users' => $users,
            'agency' => $getAgent,
            'filters' => $request->only('search'),
        ]);
    }

    function unlockUser($user_id, Request $request)
    {
        $user = User::find($user_id);
        $user->locked = 'unlocked'; //Ganti dengan nama kolom yang sesuai
        $user->save();

        return redirect()->back()->with('success', 'Berhasil buka kunci user ' . $user->name . ', NISN : ' . $user->nisn . '.');
    }

    public function queueAgentLocked($id, Request $request)
    {
        $getAgent = Agency::where('id', $id)->first();
        if (!$getAgent) {
            return redirect('kolektif');
        }

        $search = $request->input('search');

        $user = User::query()
            ->where('agency_id', $id)
            ->where('locked', 'locked')
            ->where('photo', null)
            ->where('nisn', $search);

        if ($request->wantsJson()) {
            return response()->json($user);
        }

        $users = User::query()
            ->where('agency_id', $id)
            ->where('locked', 'locked')
            ->where('photo', null)
            ->paginate(2000)
            ->withQueryString();

        return Inertia::render('auth/QueueUsersTakePictureQr', [
            'users' => $users,
            'agency' => $getAgent,
            'filters' => $request->only('search'),
        ]);
    }

    public function isiAgentLocked($id, Request $request)
    {
        $getAgent = Agency::find($id);
        if (!$getAgent) {
            return response()->json(['error' => 'Agency not found'], 404);
        }

        $users = User::where('agency_id', $id)
            ->where('locked', 'locked')
            ->where('photo', null)
            ->get();

        return response()->json([
            'users' => $users,
            'agency' => $getAgent,
            'updated_at' => now()
        ]);
    }

    public function storeAgency(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'tipe' => 'required',
        ]);

        Agency::create([
            'name' => $validated['name'],
            'code' => $validated['kode'],
            'slug' => Str::random(100), // return Str::random(100);
            'tipe' => $validated['tipe'],
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function storeUser($agency_id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:255',
            'nisn' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'username' => $validated['nisn'],
            'agency_id' => $agency_id,
            'role_id' => 2,
            'email'    => rand() . '@' . rand() . '.com',
            'password' => Hash::make('password'),
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function storeRoles(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Role::create([
            'name' => $validated['name'],
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'agencyId' => 'required' // validasi role_id
        ]);

        $agencyId = $request->input('agencyId');

        Excel::import(new UsersImport($agencyId), $request->file('file'));

        return redirect()->back()->with('success', 'Import berhasil!');
    }

    public function redirectLoginUser($slug, Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login/' . $slug);
    }


    public function prosesFotoAwal($id)
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
            $destinationFolder2025 = public_path('uploads/' . $slugAgencyName . '/2025');
            $destinationFolder2227 = public_path('uploads/' . $slugAgencyName . '/2227');
            $destinationFolder2838 = public_path('uploads/' . $slugAgencyName . '/2838');

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
                $cropY25 = ($imageHeight25 - $cropHeight25) / 2;
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

                $newImageName2025 =  $slugName . '_' . $getUser->nisn . '.jpg';
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
                $cropY27 = ($imageHeight27 - $cropHeight27) / 2;
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

                $newImageName2227 =  $slugName . '_' . $getUser->nisn . '.jpg';
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
                $cropWidth28 = 2506;
                $cropHeight38 = 3401;
                // Get the current dimensions of the image
                $imageWidth28 = $image2838->getImageWidth();
                $imageHeight38 = $image2838->getImageHeight();
                // Calculate the center of the image
                $cropX28 = ($imageWidth28 - $cropWidth28) / 2;
                $cropY38 = ($imageHeight38 - $cropHeight38) / 2;
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

                $newImageName2838 =  $slugName . '_' . $getUser->nisn . '.jpg';
                $newImagePath2838 = $destinationFolder2838 . DIRECTORY_SEPARATOR . $newImageName2838;
                $image2838->writeImage($newImagePath2838);

                $getUser->update([
                    'photo' => $newImageName2838,
                ]);

                $image2838->clear();
                $image2838->destroy();

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
}
