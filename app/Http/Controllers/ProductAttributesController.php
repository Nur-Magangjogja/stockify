<?php

namespace App\Http\Controllers;
use App\Models\ProductAttributes;
use Illuminate\Http\Request;

class ProductAttributesController extends Controller
{
    public function index()
    {
        $ProductAttributes = ProductAttributes::all();

        dd($ProductAttributes);
    }
}
