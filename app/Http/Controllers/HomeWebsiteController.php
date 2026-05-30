<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\CategoryV1;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\TrademarkV1;
use Carbon\Carbon;

class HomeWebsiteController extends Controller
{
    public function index()
    {
        dd(123);
        return view('website.index');
    }
    

    public function homeFc()
    {
        return view('website.index');
    }
}