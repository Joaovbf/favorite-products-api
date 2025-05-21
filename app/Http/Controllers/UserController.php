<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function __construct(
    )
    {
    }

    public function index()
    {
        Cache::forget('name');
        return Response::json(['data' => 'Hello World!', 'cached' => Cache::get('name')]);
    }

    public function store(){

    }

    public function show(){

    }

    public function update(){

    }

    public function destroy(){

    }

}
