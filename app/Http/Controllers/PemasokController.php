<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $data = Pemasok::all();
        return view('pemasok.index', compact('data'));
    }

    public function store(Request $request)
    {
        Pemasok::create($request->all());
        return back();
    }
}