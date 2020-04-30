<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\common_model;

class commonController extends Controller
{
    protected function common_model()
    {
        return new common_model();
    }
}
