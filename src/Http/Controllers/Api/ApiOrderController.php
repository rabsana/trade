<?php

namespace Rabsana\Trade\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Rabsana\Trade\Helpers\Json;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Actions\OrderCreatedAction;
use Rabsana\Trade\Actions\OrderCancelledAction;
use Rabsana\Trade\Exceptions\ServerErrorException;
use Rabsana\Trade\Tasks\GetOrderSymbolFromCacheTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rabsana\Trade\Exceptions\ModelNotFoundErrorException;
use Rabsana\Trade\Http\Requests\Api\Order\StoreOrderRequest;
use Rabsana\Trade\Exceptions\MarketDepthIsNotEnoughException;
use Rabsana\Trade\Http\Resources\SymbolOrder\SymbolOrderResource;
use Rabsana\Trade\Http\Resources\SymbolOrder\SymbolOrderCollection;

class ApiOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $orders = SymbolOrder::latest()
                ->statusId($request->statusId)
                ->typeId($request->typeId)
                ->orderableFilter(!empty($request->user()) ? get_class($request->user()) : NULL, optional($request->user())->id)
                ->base($request->base)
                ->quote($request->quote)
                ->pair($request->pair)
                ->search($request->search)
                ->side($request->side)
                ->token($request->token)
                ->with('orderable')
                ->with('status')
                ->with('type')
                ->paginate((int) $request->get('per_page', 15))
                ->appends($request->all());

            return (new SymbolOrderCollection($orders))->setCustomWith([
                'message'   => Lang::get("trade::symbolOrder.list")
            ]);

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Rabsana\Trade\Http\Requests\Api\Order\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {

            $symbol = app(GetOrderSymbolFromCacheTask::class)->run();
            $type = Str::ucfirst(Str::camel(Str::lower($request->get('type'))));
            $orderClass = "Rabsana\\Trade\\Orders\\$type";

            // check the class exists
            if (!class_exists($orderClass)) {
                throw new Exception("Class {$orderClass} not found.", 500);
            }

            // store the order
            $order = (new $orderClass())->store($symbol);

            // fetch the symbol again
            $order = SymbolOrder::with('orderable')
                ->with('type')
                ->with('status')
                ->findOrFail($order->id);

            app(OrderCreatedAction::class)->run($order, 'created');

            // everything is fine here
            DB::commit();

            return (new SymbolOrderResource($order))->setCustomWith([
                'message'   => Lang::get("trade::symbolOrder.orderCreatedSuccessfully")
            ]);


            // 
        } catch (MarketDepthIsNotEnoughException $e) {

            DB::rollBack();
            return Json::response($e->getCode(), $e->getMessage());

            // 
        } catch (Exception $e) {

            // there is a problem. rollback the data
            DB::rollBack();
            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $orderId)
    {
        try {

            $order = SymbolOrder::orderableFilter(!empty($request->user()) ? get_class($request->user()) : NULL, optional($request->user())->id)
                ->with('orderable')
                ->with('status')
                ->with('type')
                ->with('makers')
                ->with('takers')
                ->findOrFail($orderId);

            return (new SymbolOrderResource($order))->setCustomWith([
                'message'   => Lang::get("trade::symbolOrder.detail")
            ]);


            // 
        } catch (ModelNotFoundException $e) {

            return app(ModelNotFoundErrorException::class)->report($e)->response();

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }


    public function cancel(Request $request, $orderId)
    {
        DB::beginTransaction();
        try {

            // get order
            $order = SymbolOrder::orderableFilter(!empty($request->user()) ? get_class($request->user()) : NULL, optional($request->user())->id)
                ->cancelable()
                ->findOrFail($orderId);


            // cancel the order
            SymbolOrder::cancel($order);

            // fetch the order again
            $order = SymbolOrder::with('orderable')
                ->with('status')
                ->with('type')
                ->with('makers')
                ->with('takers')
                ->findOrFail($orderId);

            app(OrderCancelledAction::class)->run($order, 'cancelled');

            DB::commit();

            return (new SymbolOrderResource($order))->setCustomWith([
                'message'   => "سفارش شما لغو شد"
            ]);




            // 
        } catch (ModelNotFoundException $e) {

            DB::rollBack();
            return app(ModelNotFoundErrorException::class)->report($e)->response();

            // 
        } catch (Exception $e) {


            DB::rollBack();
            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }
}
