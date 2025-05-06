<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Requests\Zoho\GenerateRequest;
use Illuminate\Http\Response;

class GenerateController extends BaseController
{
    public function __invoke(GenerateRequest $request)
    {
        $data = $request->validated();
        try {
            $result = $this->service->generate_tokens(code: $data['code']);
            return response()->json(['status' => $result], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
