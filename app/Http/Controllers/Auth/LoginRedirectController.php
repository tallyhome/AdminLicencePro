<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LoginRedirectController extends Controller
{
    /**
     * Rediriger vers la page de connexion admin
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToAdminLogin()
    {
        return redirect()->route('admin.login.form');
    }
}
