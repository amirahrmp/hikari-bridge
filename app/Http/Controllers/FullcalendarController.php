<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FullcalendarController extends Controller
{
    public function index()
    {
       return view('fullcalendar');
    }
}
