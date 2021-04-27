<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    public function helloWorld()
    {
        return 'Hello World';
    }

    public function sayHello(Request $request, $id)
    {
        return $id . '.- Hello ' . $request->input('name');
    }
}
