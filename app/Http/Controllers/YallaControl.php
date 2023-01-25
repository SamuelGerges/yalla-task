<?php

namespace App\Http\Controllers;

use App\Http\Requests\YallaRequest;
use App\Models\User;
use Illuminate\Http\Request;

class YallaControl extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('yallon.create');
    }

    public function store(YallaRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create($data);
            return redirect()->back()->with('success','Created successfully');
        }catch (\Exception $e){
            dd($e);
        }

    }
}
