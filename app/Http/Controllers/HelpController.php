<?php

namespace App\Http\Controllers;

class HelpController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('help.index');
	}
}
