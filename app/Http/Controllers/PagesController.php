<?php namespace App\Http\Controllers;

use App\Http\Requests;

class PagesController extends Controller
{
	public function index()
	{
		return view( 'pages.index' );
	}

	public function about()
	{
		$first = 'Delcos';
		$last = 'Delcos';

		return view( 'pages.about', compact( 'first', 'last' ) );
	}

	public function contact()
	{
		return view( 'pages.contact' );
	}
}
