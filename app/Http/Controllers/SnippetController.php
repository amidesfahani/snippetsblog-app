<?php

namespace App\Http\Controllers;

class SnippetController extends Controller
{
	public function index()
	{
		return view('snippets');
	}

	public function create()
	{
		return view('snippets');
	}
}
