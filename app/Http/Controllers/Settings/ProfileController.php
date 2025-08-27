<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Agency;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D; // Use facade
use Milon\Barcode\Facades\DNS2DFacade as DNS2D; // Use facade
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Requests\Settings\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }
    public function editUser(Request $request): Response
    {
        return Inertia::render('settings/ProfileUser', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.edit');
    }
    public function updateUser(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect('settings/user');
        // return to_route('user.edit');
    }
    public function lockUser(ProfileUpdateRequest $request): RedirectResponse
    {
        // dd($request);
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();

        $user = User::findOrFail(auth()->user()->id);
        $user->update([
            'locked' => 'locked',
        ]);

        $getAgency = Agency::findOrFail(auth()->user()->agency_id);
        $this->genBarQr(auth()->user()->id);

        Auth::guard('web')->logout();

        return redirect('login/' . $getAgency->slug);
        // return redirect(route('logout'))->with('success', 'Sukses Keluar dan Data sukses dikunci');
    }

    function genBarQr($id)
    {
        $getUser = User::with('agency')->find($id);

        $data = $getUser->nisn; // Data yang ingin di-encode
        $type = 'C128'; // Jenis barcode (misalnya, Code 39)
        $width = 2;
        $height = 50;

        $name = $getUser->nisn . '.png';
        $barcode = DNS1D::getBarcodePNG($data, $type, $width, $height);
        $barpath = 'profile/barcodes/' . $name; // Nama file unik
        Storage::disk('public')->put($barpath, base64_decode(str_replace('data:image/png;base64,', '', $barcode)));

        $image = DNS2D::getBarcodePNGPath($data, 'QRCODE');
        $qrpath = 'profile/qrcodes/' . $name; // Nama file unik
        Storage::disk('public')->put($qrpath, file_get_contents($image));
        unlink($image); // Menghapus file sementara dari disk
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
