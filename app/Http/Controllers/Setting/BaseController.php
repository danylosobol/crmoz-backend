<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Services\SettingService;

class BaseController extends Controller
{
    public $service;
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }
}
