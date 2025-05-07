<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Requests\Zoho\StoreRequest;
use Illuminate\Http\Response;


class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $account = $this->service->create_account(['account_name' => $data['account_name'], 'account_website' => $data['account_website'], 'account_phone' => $data['account_phone']]);
            $deal = $this->service->create_deal(['deal_name' => $data['deal_name'], 'deal_stage' => $data['deal_stage'], 'account_id' => $account]);
            return $deal;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
