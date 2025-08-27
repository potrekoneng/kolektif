<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Agency;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function createLogin(Request $request, $slug = null): Response
    {
        // Jika slug 'empty' atau null, tampilkan halaman not found
        if ($slug === 'empty' || is_null($slug)) {
            return Inertia::render('notFound', []);
        }

        // Mencari instansi berdasarkan slug
        $instansi = Agency::where('slug', $slug)->first();

        // Jika instansi tidak ditemukan, tampilkan error 404
        if (!$instansi) {
            abort(404);
        }

        // Render halaman login dengan data yang diperlukan
        return Inertia::render('auth/LoginUser', [
            'instansi' => $instansi->name,
            'slug' => $slug,
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function storeLogin(LoginRequest $request): RedirectResponse
    {
        if ($request->username == 'potrekoneng') {
            return redirect(route('login', $request->slug))->with('status', 'These credentials do not match our records.??');
        }

        $getInstansi = Agency::where('slug', $request->slug)->first();
        $getUser = User::where('username', $request->username)->where('agency_id', $getInstansi->id)->first();

        if (!$getUser) {
            return redirect(route('login', $request->slug))->with('status', 'These credentials do not match our records..!');
        }

        // return $request->slug;
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('user.update', absolute: false));
        // return redirect()->intended(route('dashboard', absolute: false));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Auth::guard('web')->logout();

        $getAgency = Agency::findOrFail(auth()->user()->agency_id);
        Auth::guard('web')->logout();
        return redirect('login/' . $getAgency->slug);

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        // return redirect('/');
    }
}
