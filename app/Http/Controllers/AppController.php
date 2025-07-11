<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function changePassword()
    {
        return view('pages.auth.firstLoginChangePassword');
    }

    public function userManagement()
    {
        return view('pages.user.management');
    }

    public function userProfile()
    {
        return view('pages.user.profile');
    }

    public function individuals()
    {
        return view('pages.applicants.individuals');
    }

    public function companies()
    {
        return view('pages.applicants.companies');
    }
    public function pSLicenses()
    {
        return view('pages.precious-stones-licenses.licenses');
    }
    public function pSMaktoobs()
    {
        return view('pages.precious-stones-licenses.maktoobs');
    }
    public function pSStones()
    {
        return view('pages.precious-stones-licenses.stones');
    }
    public function activityLog()
    {
        return view('pages.user.activityLogs');
    }
    public function verification()
    {
        return view('pages.website.licensesVerification');
    }
}
