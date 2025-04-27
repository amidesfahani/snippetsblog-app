<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
	public function showLogin()
	{
		return view('login');
	}

	public function showRegister()
	{
		return view('login');
	}
}
