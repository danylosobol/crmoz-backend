<?php

namespace App\Http\Services;

use App\Models\Setting;
use DB;
use Illuminate\Http\Response;

class SettingService
{
  public function index($keys)
  {
    return Setting::whereIn('key', $keys)->get();
  }
  public function store($key, $value)
  {
    return Setting::updateOrCreate(['key' => $key], ['value' => $value]);
  }

  public function show($key)
  {
    $setting = Setting::find($key);
    if ($setting->isEmpty()) {
      abort(Response::HTTP_NOT_FOUND, 'This setting doesn\'t exist.');
    }
    return $setting;
  }

  public function bulkStore($data)
  {
    try {
      $storedSettings = [];
      DB::beginTransaction();
      foreach ($data as $settingData) {
        $setting = $this->store($settingData['key'], $settingData['value']);
        $storedSettings[] = $setting;
      }
      DB::commit();
      return $storedSettings;
    } catch (\Exception $e) {
      dd($e->getMessage());
      DB::rollBack();
      abort(Response::HTTP_BAD_REQUEST, 'Settings were not stored.');
    }
  }
}