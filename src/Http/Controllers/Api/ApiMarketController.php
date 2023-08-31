<?php

namespace Rabsana\Trade\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rabsana\Trade\Exceptions\ServerErrorException;
use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Http\Resources\Market\LatestFilledOrdersCollection;
use Rabsana\Trade\Tasks\GetLatestFilledOrders;
use Rabsana\Trade\Tasks\GetMakersOrderTask;
use Rabsana\Trade\Tasks\GetTakersOrderTask;

class ApiMarketController extends Controller
{

    public function orderBook(Request $request, $symbol = "BTCUSDT")
    {
        try {

            $takers = app(GetTakersOrderTask::class)->run($symbol, (int)$request->get('limit', 15));
            $makers = app(GetMakersOrderTask::class)->run($symbol, (int)$request->get('limit', 15));

            return Json::response(
                200,
                '',
                [
                    'takers'    => $this->makeOrderBookArray($takers),
                    'makers'    => $this->makeOrderBookArray($makers),
                ]
            );

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function takerOrderBook(Request $request, $symbol = "BTCUSDT")
    {
        try {

            $takers = app(GetTakersOrderTask::class)->run($symbol, (int)$request->get('limit', 15));

            return Json::response(
                200,
                '',
                $this->makeOrderBookArray($takers),
            );

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function makerOrderBook(Request $request, $symbol = "BTCUSDT")
    {
        try {

            $makers = app(GetMakersOrderTask::class)->run($symbol, (int)$request->get('limit', 15));

            return Json::response(
                200,
                '',
                $this->makeOrderBookArray($makers),
            );

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function makeOrderBookArray($orders): array
    {
        $array = [];
        $user_id = auth('api')->id();

        if (!empty($orders)) {

            foreach ($orders as $item) {

                $price = $item->price;

                $qty = (!empty($array[$price])) ? Math::add((float) $item->base_qty, (float) $array[$price]['qty']) : $item->base_qty;
                $filled = (!empty($array[$price])) ? Math::add((float) $item->filled_base_qty, (float) $array[$price]['filled']) : $item->filled_base_qty;
                if($item->orderable_type == 'App\User') {
                    $array[$price]['mine'][] = (int) $item->orderable_id; 
                }

                $array[$price] = [
                    'price'         => $price,
                    'qty'           => $qty,
                    'total'         => Math::multiply(Math::subtract((float)$qty, (float)$filled), (float) $price),
                    'filled'        => $filled,
                    'filledPercent' => round(($qty == 0) ? 0 : Math::divide((float)Math::multiply((float)$filled, 100), (float) $qty), 2),
                    'mine'          => $array[$price]['mine'] ?? []
                ];
            }
        }

        return collect($array)->values()->all();
    }

    public function latestOrders(Request $request, $symbol = "BTCUSDT")
    {
        try {

            $latestOrders = app(GetLatestFilledOrders::class)->run($symbol, (int)$request->get('limit', 10));

            return (new LatestFilledOrdersCollection($latestOrders));

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }
}
