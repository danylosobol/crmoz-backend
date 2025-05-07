<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\IndexRequest;
use App\Http\Resources\SettingResource;
use Illuminate\Http\Response;


class IndexController extends BaseController
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        try {
            $settings = $this->service->index($data['keys']);
            return SettingResource::collection($settings);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
