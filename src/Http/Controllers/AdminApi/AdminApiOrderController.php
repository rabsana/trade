<?php

namespace Rabsana\Trade\Http\Controllers\AdminApi;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\ServerErrorException;
use Rabsana\Trade\Http\Resources\SymbolOrder\SymbolOrderCollection;
use Rabsana\Trade\Http\Resources\SymbolOrder\SymbolOrderResource;
use Rabsana\Trade\Models\SymbolOrder;

class AdminApiOrderController extends Controller
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
                ->date($request->date)
                ->statusId($request->statusId)
                ->typeId($request->typeId)
                ->base($request->base)
                ->quote($request->quote)
                ->pair($request->pair)
                ->side($request->side)
                ->search($request->search)
                ->token($request->token)
                ->searchUser($request->user)
                ->with('orderable')
                ->with('status')
                ->with('type')
                ->paginate($request->get('perPage', 10))
                ->appends($request->all());

            if ($request->wantsJson()) {
                return (new SymbolOrderCollection($orders))->setCustomWith([
                    'message'   => Lang::get("trade::symbolOrder.list")
                ]);
            }

            // return view
            return view("rabsana-trade::admin.orders.index", [
                'orders'   => $orders
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return;
        DB::beginTransaction();

        try {

            // 
        } catch (Exception $e) {

            DB::rollBack();

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $orderId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $orderId)
    {
        try {

            $order = SymbolOrder::with('orderable')
                ->with('status')
                ->with('type')
                ->with('makers')
                ->with('takers')
                ->findOrFail($orderId);

            if ($request->wantsJson()) {
                return (new SymbolOrderResource($order))->setCustomWith([
                    'message'   => Lang::get("trade::symbolOrder.detail")
                ]);
            }

            return view("rabsana-trade::admin.orders.show", [
                'order' => $order
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $orderId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $orderId)
    {
        return;
        DB::beginTransaction();

        try {

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  $orderId
     * @return \Illuminate\Http\Response
     */
    public function destroy($orderId)
    {
        return;
        DB::beginTransaction();

        try {
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
