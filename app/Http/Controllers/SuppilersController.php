<?php

namespace App\Http\Controllers;
use App\Models\Suppilers;
use Illuminate\Http\Request;

class SuppilersController extends Controller
{
    public function index()
    {
        $Suppilers = Suppilers::all();

        dd($Suppilers);
    }
}
