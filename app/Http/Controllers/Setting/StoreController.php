<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\StoreRequest;
use App\Http\Resources\SettingResource;
use Illuminate\Http\Response;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $result = $this->service->bulkStore($data['settings']);
            return SettingResource::collection($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
