<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Check if the request coming from
     * verify email.
     *
     * @return  bool
     */
    private function fromVerifyEmail()
    {
        $segments = explode('/', parse_url(url()->previous(), PHP_URL_PATH));
        
        if (count($segments) === 5 && $segments[1] === 'email' && $segments[2] === 'verify') {
            return true;
        }

        return false;
    }

    /**
     * Show the login page.
     *
     * @return  \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function loginView()
    {
        if ($this->fromVerifyEmail()) {
            $loginRoute = config('app.spa_url') . '/login';

            return redirect()->to($loginRoute . '?origin=' . urlencode(url()->previous()));
        }

        return view('login', [
            'title' => 'Login'
        ]);
    }

    /**
     * Authenthicate admin.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function authenthicate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->input('remember') ? true : false;

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
