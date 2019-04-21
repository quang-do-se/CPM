<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/15/19
 * Time: 11:29 PM
 */

namespace App\Http\Controllers;

class SearchController extends Controller
{
    public function index()
    {
        return view('presentation.search');
    }
}
