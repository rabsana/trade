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
use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Http\Requests\AdminApi\Commission\StoreCommissionRequest;
use Rabsana\Trade\Http\Requests\AdminApi\Commission\UpdateCommissionRequest;
use Rabsana\Trade\Http\Resources\Commission\CommissionCollection;
use Rabsana\Trade\Http\Resources\Commission\CommissionResource;
use Rabsana\Trade\Models\Commission;
use Rabsana\Trade\Models\CommissionCondition;

class AdminApiCommissionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $commissions = Commission::latest()
                ->name($request->name)
                ->symbolQuote($request->symbol_quote)
                ->search($request->search)
                ->with('conditions')
                ->paginate($request->get('perPage', 15))
                ->appends($request->all());


            if ($request->wantsJson()) {

                return (new CommissionCollection($commissions))->setCustomWith([
                    'message'   => Lang::get('trade::commission.list')
                ]);
            }

            return view("rabsana-trade::admin.commissions.index", [
                'commissions' => $commissions
            ]);


            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }

    public function create()
    {
        return view("rabsana-trade::admin.commissions.create");
    }

    public function store(StoreCommissionRequest $request)
    {
        DB::beginTransaction();
        try {

            // store commission
            $commission = Commission::create([
                'name'              => $request->name,
                'taker_fee'         => $request->taker_fee,
                'maker_fee'         => $request->maker_fee,
                'symbol_quote'      => $request->symbol_quote
            ]);


            // store conditions
            $conditions = [];
            foreach ($request->conditions as $item) {
                $conditions[] = array_merge($item, [
                    'commission_id' => $commission->id,
                    'created_at'    => now()
                ]);
            }
            CommissionCondition::insert($conditions);

            // fetch again commission
            $commission = Commission::with('conditions')
                ->findOrFail($commission->id);

            // everything is fine here
            DB::commit();

            if ($request->wantsJson()) {

                return (new CommissionResource($commission))->setCustomWith([
                    'message'   => Lang::get('trade::commission.createdSuccessfully')
                ]);
            }

            return back()->with('success',  Lang::get('trade::commission.createdSuccessfully'));

            // 
        } catch (Exception $e) {

            DB::rollBack();

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function show(Request $request, $commissionId)
    {
        try {

            // get the commission
            $commission = Commission::with('conditions')
                ->findOrFail($commissionId);

            if ($request->wantsJson()) {
                return (new CommissionResource($commission))->setCustomWith([
                    'message'   => Lang::get('trade::commission.detail')
                ]);

                // 
            }

            // 
        } catch (ModelNotFoundException $e) {

            return app(ModelNotFoundErrorException::class)->report($e)->response();

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function edit(Request $request, $commissionId)
    {
        try {

            // get the commission
            $commission = Commission::with('conditions')
                ->findOrFail($commissionId);

            if ($request->wantsJson()) {
                return (new CommissionResource($commission))->setCustomWith([
                    'message'   => Lang::get('trade::commission.detail')
                ]);
                // 
            }

            return view("rabsana-trade::admin.commissions.edit", [
                'commission' => $commission,
            ]);

            // 
        } catch (ModelNotFoundException $e) {

            return app(ModelNotFoundErrorException::class)->report($e)->response();

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function update(UpdateCommissionRequest $request, $commissionId)
    {
        DB::beginTransaction();
        try {

            // get the commission
            $commission = Commission::findOrFail($commissionId);

            // update commission
            $commission->update([
                'name'              => $request->name,
                'taker_fee'         => $request->taker_fee,
                'maker_fee'         => $request->maker_fee,
                'symbol_quote'      => $request->symbol_quote
            ]);

            // update conditions
            $conditions = [];
            foreach ($request->conditions as $item) {
                $conditions[] = array_merge($item, [
                    'commission_id' => $commission->id,
                    'created_at'    => now()
                ]);
            }
            // first delete the commission's conditions and insert the new conditions
            CommissionCondition::where('commission_id', $commission->id)->delete();
            CommissionCondition::insert($conditions);

            // fetch again commission
            $commission = Commission::with('conditions')
                ->findOrFail($commission->id);

            // everything is fine here
            DB::commit();

            if ($request->wantsJson()) {

                return (new CommissionResource($commission))->setCustomWith([
                    'message'   => Lang::get('trade::commission.updatedSuccessfully')
                ]);
            }

            return back()->with('success',  Lang::get('trade::commission.updatedSuccessfully'));

            // 
        } catch (ModelNotFoundException $e) {

            DB::rollBack();

            return app(ModelNotFoundErrorException::class)->report($e)->response();

            // 
        } catch (Exception $e) {

            DB::rollBack();

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function destroy(Request $request, $commissionId)
    {
        DB::beginTransaction();
        try {

            // get the commission
            $commission = Commission::findOrFail($commissionId);

            $commission->delete();

            // everything is fine here
            DB::commit();

            if ($request->wantsJson()) {
                return Json::response(200, Lang::get("trade::commission.deletedSuccessfully"));
            }

            return back()->with('success', Lang::get("trade::commission.deletedSuccessfully"));


            // 
        } catch (ModelNotFoundException $e) {

            DB::rollBack();

            return app(ModelNotFoundErrorException::class)->report($e)->response();

            // 
        } catch (Exception $e) {

            DB::rollBack();

            return app(ServerErrorException::class)->report($e)->response();
        }
    }
}
