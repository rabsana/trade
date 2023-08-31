<?php

namespace Rabsana\Trade\Http\Controllers\AdminApi;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\ModelNotFoundErrorException;
use Rabsana\Trade\Exceptions\ServerErrorException;
use Rabsana\Trade\Http\Requests\AdminApi\Symbol\StoreSymbolRequest;
use Rabsana\Trade\Http\Requests\AdminApi\Symbol\UpdateSymbolRequest;
use Rabsana\Trade\Http\Resources\Symbol\SymbolCollection;
use Rabsana\Trade\Http\Resources\Symbol\SymbolResource;
use Rabsana\Trade\Models\Symbol;

class AdminApiSymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {


            $symbols = Symbol::base($request->base)
                ->quote($request->quote)
                ->pair($request->pair)
                ->buyIsActive($request->buy_is_active)
                ->sellIsActive($request->sell_is_active)
                ->search($request->search)
                ->paginate($request->get('perPage', 15))
                ->appends($request->all());

            // return json
            if ($request->wantsJson()) {
                return (new SymbolCollection($symbols))->setCustomWith([
                    'message'   => Lang::get("trade::symbol.list")
                ]);
            }

            // return view
            return view("rabsana-trade::admin.symbols.index", [
                'symbols'   => $symbols
            ]);

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("rabsana-trade::admin.symbols.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Rabsana\Trade\Http\Requests\AdminApi\Symbol\StoreSymbolRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSymbolRequest $request)
    {

        DB::beginTransaction();

        try {

            // store sybmol data
            $symbol = Symbol::create(
                collect($request->validated())->only([
                    'base',
                    'quote',
                    'pair',
                    'base_name',
                    'quote_name',
                    'pair_name',
                    'description',
                    'priority',
                    'buy_is_active',
                    'sell_is_active'
                ])
                    ->toArray()
            );

            // store symbol validation
            $symbol->validation()->create(
                collect($request->validated())->only([
                    'min_qty',
                    'max_qty',
                    'scale_qty',
                    'min_price',
                    'max_price',
                    'scale_price',
                    'min_notional',
                    'max_notional',
                    'scale_notional',
                    'percent_order_price_up',
                    'percent_order_price_down',
                    'percent_order_price_minute',
                    'average_price_source_is_market'
                ])->toArray()
            );

            // store order types
            $symbol->types()->attach($request->types);

            // get the symbol
            $symbol = Symbol::with('types')
                ->with('validation')
                ->findOrFail($symbol->id);


            DB::commit();

            // return json
            if ($request->wantsJson()) {
                return (new SymbolResource($symbol))->setCustomWith([
                    'message'   => Lang::get('trade::symbol.symbolCreated')
                ]);
            }

            // return back
            return back()->with('success', Lang::get('trade::symbol.symbolCreated'));

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
     * @param  $symbolId
     * @return \Illuminate\Http\Response
     */
    public function show($symbolId)
    {
        try {
            $symbol = Symbol::with('types')
                ->with('validation')
                ->findOrFail($symbolId);

            return (new SymbolResource($symbol))->setCustomWith([
                'message'   => Lang::get('trade::symbol.detail')
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
     * Edit the specified resource.
     *
     * @param  $symbolId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $symbolId)
    {
        try {

            $symbol = Symbol::with('types')
                ->with('validation')
                ->findOrFail($symbolId);

            // return json
            if ($request->wantsJson()) {
                return (new SymbolResource($symbol))->setCustomWith([
                    'message'   => Lang::get('trade::symbol.detail')
                ]);
            }

            return view("rabsana-trade::admin.symbols.edit", [
                'symbol'  => $symbol
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
     * @param  \Rabsana\Trade\Http\Requests\AdminApi\Symbol\UpdateSymbolRequest  $request
     * @param  $symbolId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSymbolRequest $request, $symbolId)
    {
        DB::beginTransaction();

        try {

            // get the symbol
            $symbol = Symbol::findOrFail($symbolId);

            // update symbol info
            $symbol->update(
                collect($request->validated())->only([
                    'base',
                    'quote',
                    'pair',
                    'base_name',
                    'quote_name',
                    'pair_name',
                    'description',
                    'priority',
                    'buy_is_active',
                    'sell_is_active'
                ])
                    ->toArray()
            );

            // update symbol validation
            $symbol->validation()->update(
                collect($request->validated())->only([
                    'min_qty',
                    'max_qty',
                    'scale_qty',
                    'min_price',
                    'max_price',
                    'scale_price',
                    'min_notional',
                    'max_notional',
                    'scale_notional',
                    'percent_order_price_up',
                    'percent_order_price_down',
                    'percent_order_price_minute',
                    'average_price_source_is_market'
                ])->toArray()
            );

            // update symbol types
            $symbol->types()->sync($request->types);

            // get the symbol
            $symbol = Symbol::with('types')
                ->with('validation')
                ->findOrFail($symbol->id);


            DB::commit();

            if ($request->wantsJson()) {
                return (new SymbolResource($symbol))->setCustomWith([
                    'message'   => Lang::get('trade::symbol.symbolUpdated')
                ]);
            }

            return back()->with('success',  Lang::get('trade::symbol.symbolUpdated'));

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
     * @param  $symbolId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $symbolId)
    {
        DB::beginTransaction();

        try {

            // get the symbol
            $symbol = Symbol::findOrFail($symbolId);

            // delete it
            $symbol->delete();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'status'        => 200,
                    'success'       => true,
                    'message'       => Lang::get('trade::symbol.symbolDeleted'),
                    'errors'        => [],
                    'data'          => []
                ]);
            }

            return back()->with('success',  Lang::get('trade::symbol.symbolDeleted'));

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

    public function changePriority(Symbol $symbol, Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'priority' => 'required|numeric'
            ]);

            $symbol->update([
                'priority' => $request->priority
            ]);

            $symbol->refresh();

            DB::commit();

            if ($request->wantsJson()) {
                return (new SymbolResource($symbol))->setCustomWith([
                    'message'   => "اولویت با موفقیت ویرایش شد"
                ]);
            }

            return back()->with('success', "با موفقیت انجام شد");

            // 
        } catch (Exception $e) {

            DB::rollback();
            return app(ServerErrorException::class)->report($e)->response();
        }
    }
}
