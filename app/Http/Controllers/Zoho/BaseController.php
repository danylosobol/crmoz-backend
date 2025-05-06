<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Controllers\Controller;
use App\Http\Services\ZohoService;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $service;

    public function __construct(ZohoService $service)
    {
        $this->service = $service;
    }
}
