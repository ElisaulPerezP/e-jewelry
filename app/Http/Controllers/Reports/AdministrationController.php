<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdministrationController extends Controller
{
    public function administration(): View
    {
        return view('administration.administration');
    }
    public function dispatch(): View
    {
        return view('administration.dispatch');
    }
}
